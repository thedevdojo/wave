<?php
    use function Laravel\Folio\{name};
    use Livewire\Volt\Component;
    use Filament\Notifications\Notification;
    use Livewire\WithFileUploads;
    name('media');

    use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;

    new class extends Component implements HasForms, HasActions
	{
        use InteractsWithActions;
    use InteractsWithForms;
        use WithFileUploads;

        public $record;
        
        public $upload;
        public $uploadFile;
        public $folder = '/';
        public $storageURL = '';
        public $files;
        public $disk;
        public $breadcrumbs;
        public $folderName;

        public $selectedFile;
        public $destinationFolder;

        public function mount($disk = 'public'){
            $this->record = App\Models\User::first();
            $this->storageURL = $this->storage($disk)->url('/');
            $this->disk = $disk;
            $this->loadFilesInCurrentFolder();
            $this->breadcrumbsRefresh();
        }
        
        #[Computed]
        public function isRootDirectory()
        {
           $rootDir = $this->folder == '/';
           return $rootDir;
        }

        public function setSelectedFile($file){
            $this->selectedFile = $file;
        }

        #[Computed]
        public function getFoldersInCurrentDirectory()
        {
            $folders = $this->storage($this->disk)->directories($this->folder);

            $folders = array_map(function($folder) {
                return basename($folder);
            }, $folders);

            sort($folders);
            return $folders;
        }

        private function loadFilesInCurrentFolder(){
            $this->files = $this->getFilesInDir($this->folder);
        }
    
        public function storage($disk = false){
            // We want to get the class from the Storage facade, this is probably Illuminate\Filesystem\FilesystemManager
            $storageClass = get_class(\Illuminate\Support\Facades\Storage::getFacadeRoot());

            // create a new instance of this object to be used
            $classInstance = new $storageClass(app());

            // if the disk is set by default return the disk passed in
            if($disk) $classInstance = $classInstance->disk($disk);

            return $classInstance;
        }

        #[Computed]
        public function breadcrumbsRefresh(){
            $crumbs = array_filter(explode('/', trim($this->folder, '/')));
            $breadcrumbs = [];

            foreach($crumbs as $index => $crumb){
                $depth = 0;
                $location = '';
                while($depth <= $index){
                    $location .= '/' . $crumbs[$depth];
                    $depth++;
                }
                array_push($breadcrumbs, (object)[
                    'display' => $crumb,
                    'location' => $location
                ]);
            }

            $this->breadcrumbs =  $breadcrumbs;
        }

        private function getFilesInDir($dir){
            $files = [];
            $thumbnails = [];
            $thumbnail_names = [];

            $storageItems = $this->storage($this->disk)->listContents($dir)->sortByPath()->toArray();

            foreach ($storageItems as $item) {
                    if ($item['type'] == 'dir') {
                        $files[] = (object)[
                            'name'          => $item['basename'] ?? basename($item['path']),
                            'type'          => 'folder',
                            'path'          => $this->storage($this->disk)->url($item['path']),
                            'relative_path' => $item['path'],
                            'items'         => '',
                            'last_modified' => '',
                        ];
                    } else {
                        if (empty(pathinfo($item['path'], PATHINFO_FILENAME)) && !config('voyager.hidden_files')) {
                            continue;
                        }
                        // Its a thumbnail and thumbnails should be hidden
                        if (\Illuminate\Support\Str::endsWith($item['path'], $thumbnail_names)) {
                            $thumbnails[] = $item;
                            continue;
                        }
                        $mime = 'file';
                        if (class_exists(\League\MimeTypeDetection\ExtensionMimeTypeDetector::class)) {
                            $mime = (new \League\MimeTypeDetection\ExtensionMimeTypeDetector())->detectMimeTypeFromFile($item['path']);
                        }
                        $files[] = (object)[
                            'name'          => $item['basename'] ?? basename($item['path']),
                            'filename'      => $item['filename'] ?? basename($item['path'], '.'.pathinfo($item['path'])['extension']),
                            'type'          => $item['mimetype'] ?? $mime,
                            'url'          => $this->storage($this->disk)->url($item['path']),
                            'relative_path' => $item['path'],
                            'size'          => $this->formatSize(($item['size'] ?? $item->fileSize())),
                            'last_modified' => $item['timestamp'] ?? $item->lastModified(),
                            'thumbnails'    => [],
                        ];
                    }
                }

                foreach ($files as $key => $file) {
                    foreach ($thumbnails as $thumbnail) {
                        if ($file['type'] != 'folder' && Str::startsWith($thumbnail['filename'], $file['filename'])) {
                            $thumbnail['thumb_name'] = str_replace($file['filename'].'-', '', $thumbnail['filename']);
                            $thumbnail['path'] = $this->storage($this->disk)->url($thumbnail['path']);
                            $files[$key]['thumbnails'][] = $thumbnail;
                        }
                    }
                }

                return $files;
        }
        

        public function goToDirectory($path){
            if($path == '/'){
                $this->folder = '/';
            } else {
                $this->folder = '/' . $path;
            }
            $this->loadFilesInCurrentFolder();
            $this->breadcrumbsRefresh();
        }

        public function createNewFolder()
        {
            // Validate the input
            $this->validate([
                'folderName' => 'required|min:1|max:255'
            ]);

            // Sanitize the folder name to prevent directory traversal attacks
            $folderName = str_replace(['/', '\\'], '', $this->folderName);
            $folderPath = $this->stripDoubleSlashesFromString($this->folder . '/' . $folderName);

            // Check if the folder already exists
            if (Storage::disk()->exists($folderPath)) {
                $this->addError('folderName', 'This folder already exists');
                return;
            }

            // Create the folder
            $this->storage($this->disk)->makeDirectory($folderPath);

            // Your logic to create the folder goes here
            // ...

            $this->refresh();

            // Clear the input and close the modal
            $this->folderName = '';
            $this->dispatch('close-modal', id: 'create-folder-modal');
        }

        public function refresh(){
            $this->loadFilesInCurrentFolder();
        }

        public function moveFileOrFolder()
        {
            // if destination folder is null it is either up one directory .. or the first folder
            if($this->destinationFolder == null){
                $this->destinationFolder = '..';
                if($this->isRootDirectory()){
                    $folderInDirectory = $this->getFoldersInCurrentDirectory();
                    $this->destinationFolder = $folderInDirectory[0];
                } 
            }

            
            $this->validate([
                'selectedFile' => 'required|min:1',
            ]);


            $sourcePath = $this->stripDoubleSlashesFromString($this->folder . '/' . $this->selectedFile['name']);
    
            if ($this->destinationFolder === '..') {
                $destinationPath = $this->stripDoubleSlashesFromString(dirname($this->folder) . '/' . $this->selectedFile['name']);
            } else {
                $destinationPath = $this->stripDoubleSlashesFromString($this->folder . '/' . $this->destinationFolder . '/' . $this->selectedFile['name']);
            }

            if (Storage::exists($sourcePath)) {
                Storage::move($sourcePath, $destinationPath);
            }

            // Clear selection and close modal
            $this->selectedFile = null;
            $this->destinationFolder = null;
            $this->dispatch('close-modal', id: 'move-file-modal');

            Notification::make()
                ->title('Successfully moved file')
                ->success()
                ->send();

            // Update the file list
            $this->refresh();
        }

        private function stripDoubleSlashesFromString($string){
            return preg_replace('/\/+/', '/', $string);
        }

        // Upload functionality

        public function updatedUpload()
        {
            $this->validate([
                'upload' => 'required|file|max:10240', // 10MB Max
            ]);

            $fileName = $this->upload->getClientOriginalName();
            $filePath = $this->stripDoubleSlashesFromString($this->folder . '/' . $fileName);

            // Check if file already exists
            if ($this->storage($this->disk)->exists($filePath)) {
                $this->addError('upload', 'A file with this name already exists.');
                return;
            }

            // Store the file
            $path = $this->upload->storeAs($this->folder, $fileName, $this->disk);

            if ($path) {
                Notification::make()
                    ->title('File uploaded successfully')
                    ->success()
                    ->send();

                $this->upload = null; // Reset the upload property
                $this->refresh(); // Refresh the file list
            } else {
                Notification::make()
                    ->title('File upload failed')
                    ->danger()
                    ->send();
            }
        }

        public function getUploadRules()
        {
            return [
                'upload' => [
                    'required',
                    'file',
                    'max:10240', // 10MB Max
                ],
            ];
        }

        public function getUploadMessages()
        {
            return [
                'upload.required' => 'Please select a file to upload.',
                'upload.file' => 'The uploaded file is not valid.',
                'upload.max' => 'The file size should not exceed 10MB.',
            ];
        }

        // end upload functionality

        // start delete functionality
        public function deleteAction(): Action
        {
            $file_or_folder = ($this->selectedFile && $this->selectedFile['type'] === 'folder') ? 'folder' : 'file';

            return Action::make('delete')
            ->label('Delete ' . $file_or_folder)
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalDescription('Are you sure you want to delete this ' . $file_or_folder . '?')
            ->action(function () {
                if (!$this->selectedFile) {
                    Notification::make()
                        ->title('No file selected')
                        ->danger()
                        ->send();
                    return;
                }

                $this->deleteFile();
            })->view('wave::media.views.header.delete');
        }

        public function triggerDeleteAction()
        {
            $this->mountAction('delete');
        }

        private function deleteFile()
        {
            $path = $this->stripDoubleSlashesFromString($this->folder . '/' . $this->selectedFile['name']);

            if ($this->selectedFile['type'] === 'folder') {
                // Delete folder and its contents
                $this->storage($this->disk)->deleteDirectory($path);
            } else {
                // Delete single file
                $this->storage($this->disk)->delete($path);
            }

            Notification::make()
                ->title($this->selectedFile['type'] === 'folder' ? 'Folder deleted successfully' : 'File deleted successfully')
                ->success()
                ->send();

            $this->selectedFile = null;
            $this->refresh();
        }
        // end delete functionality

        private function formatSize($bytes) {
            if ($bytes < 1024) {
                return $bytes . ' B';
            } elseif ($bytes < 1048576) {
                return round($bytes / 1024, 1) . ' KB';
            } elseif ($bytes < 1073741824) {
                return round($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes < 1099511627776) {
                return round($bytes / 1073741824, 1) . ' GB';
            } else {
                return round($bytes / 1099511627776, 1) . ' TB';
            }
        }

    }
?>
<x-filament-panels::page>
    @volt('media')
        <div class="flex overflow-hidden relative justify-start items-start w-full h-full bg-white rounded-xl border shadow-sm border-zinc-200/50">
            <div class="w-full h-full">
                <p class="hidden" wire:click="isRootDirectory">test</p>
                <div x-data="{ 
                        active: @entangle('selectedFile'),
                        clientSideActive: false,
                        files: @entangle('files'), 
                        storageURL: @entangle('storageURL'),
                        activeFileDrawer: true,
                        isActiveFile(file) {
                            if(!this.active){
                                return false;
                            }
                            
                            return this.active.relative_path == file.relative_path;
                        },
                        activeFileSelected() {
                            if(!this.active) {
                                return false;
                            }
                            return true;
                        },
                        setSelectedFileNull(){
                            $wire.setSelectedFile(null);
                            this.active=null;
                        },
                        getFullImagePath(){
                            if(this.active){
                                return this.storageURL + this.active.relative_path
                            } else {
                                return '';
                            }
                        },
                        handleKeydown(event) {
                            // Check if an input element is focused
                            if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
                                return; // Exit the function if an input is focused
                            }

                            if (event.code === 'Space' || event.keyCode === 32) {
                                event.preventDefault(); // Prevent page scroll
                                window.dispatchEvent(new CustomEvent('open-file-modal', { detail: { file: this.active }}));

                            } else if (event.code === 'Delete' || event.keyCode === 46 || event.code === 'Backspace' || event.keyCode === 8) {
                                event.preventDefault();
                                console.log('Delete key pressed');
                                // Your delete key action here
                                if (this.activeFileSelected()) {
                                    $wire.triggerDeleteAction();
                                }
                            } else if (event.code === 'Escape' || event.keyCode === 27){
                                window.dispatchEvent(new CustomEvent('close-file-modal'));
                            }
                        },
                        handleFileDoubleClick(file){
                            if(file.type == 'folder'){
                                this.goToActiveDirectory();
                            } else {
                                window.dispatchEvent(new CustomEvent('open-file-modal', { detail: { file: file }}));
                            }
                        },
                        goToActiveDirectory(){
                            $wire.goToDirectory(this.active.relative_path)
                        },
                        isUploading: false, 
                        progress: 0
                    }"
                    x-init="
                        $watch('active', function(value){
                            if(value == null){
                                clientSideActive = false;
                            } else {
                                clientSideActive = true;
                            }
                        })
                    "
                    @go-to-active-directory.window="goToActiveDirectory()"
                    x-on:keydown.window="handleKeydown"
                    @trigger-delete-action.window="$wire.triggerDeleteAction"
                    class="flex flex-col justify-start items-center w-full h-full bg-white">
                    
                    <div class="relative p-5 pb-0 w-full bg-white border-b border-zinc-200/70">
                        @include('wave::media.views.header')
                        @include('wave::media.views.breadcrumbs')
                    </div>

                    <div class="flex justify-start items-stretch w-full h-full bg-white">
                        @include('wave::media.views.files')
                        @include('wave::media.views.active-file')
                    </div>
                    @include('wave::media.views.full-screen-file-modal')
                </div>
            </div>
            
        <x-filament-actions::modals />
        </div>
    @endvolt
    
</x-filament-panels::page>
