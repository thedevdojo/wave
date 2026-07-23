<?php

namespace Wave\Http\Livewire\Media;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

    new class extends Component implements HasForms, HasActions
	{
        use InteractsWithActions;
    use InteractsWithForms;
        use WithFileUploads;

        public $record;
        
        public $upload;
        public $uploadFile;

        #[Url]
        public $folder = '/';
        public $storageURL = '';
        public $files;
        public $disk;
        public $breadcrumbs;
        public $folderName;

        public $search;
        public $searchResults = null;

        public $selectedFile;
        public $destinationFolder;

        public $fileOrFolderCopied = null;

        public function mount($disk = 'public'){
            $this->record = User::first();
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
                            'id' => uniqid(),
                            'name'          => $item['basename'] ?? basename($item['path']),
                            'original_name' => $item['basename'] ?? basename($item['path']),
                            'type'          => 'folder',
                            'path'          => $this->storage($this->disk)->url($item['path']),
                            'relative_path' => $item['path'],
                            'items'         => count($this->storage($this->disk)->files($item['path'])),
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
                            'original_name' => $item['basename'] ?? basename($item['path']),
                            'filename'      => $item['filename'] ?? basename($item['path'], '.'.pathinfo($item['path'])['extension']),
                            'type'          => $item['mimetype'] ?? $mime,
                            'url'          => $this->storage($this->disk)->url($item['path']),
                            'relative_path' => $item['path'],
                            'size'          => $this->formatSize(($item['size'] ?? $item->fileSize())),
                            'last_modified' => $item['timestamp'] ?? \Carbon\Carbon::parse($item->lastModified())->diffForHumans(),
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
            $this->selectedFile=null;
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
            $file_or_folder = $this->fileOrFolder();

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

        public function duplicate(){

            $source = $this->storage($this->disk)->path($this->selectedFile['relative_path']);

            $destination = $this->getUniqueDestination($source);

            try {
                if (File::isDirectory($source)) {
                    File::copyDirectory($source, $destination);
                } else {
                    File::copy($source, $destination);
                }
            } catch (\Exception $e) {
                Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
                return;
            }

            $file_or_folder = $this->fileOrFolder();
            Notification::make()
                ->title('Successfully duplicated ' . $file_or_folder)
                ->success()
                ->send();

            $this->refresh();
        }

        protected function getUniqueDestination($source)
        {
            $directory = dirname($source);
            $name = pathinfo($source, PATHINFO_FILENAME);
            $extension = pathinfo($source, PATHINFO_EXTENSION);

            $counter = 2;
            $destination = $source;

            while (File::exists($destination)) {
                if ($extension) {
                    $destination = $directory . '/' . $name . " ($counter)." . $extension;
                } else {
                    $destination = $directory . '/' . $name . " ($counter)";
                }
                $counter++;
            }

            return $destination;
        }

        private function fileOrFolder(){
            return ($this->selectedFile && $this->selectedFile['type'] === 'folder') ? 'folder' : 'file';
        }

        public function copy(){
            $this->fileOrFolderCopied = $this->selectedFile['relative_path'];
        }

        public function paste(){
            if (!$this->fileOrFolderCopied) {
                Notification::make()
                    ->title('No file or folder selected for copying')
                    ->warning()
                    ->send();
                return;
            }

            $source = $this->storage($this->disk)->path($this->fileOrFolderCopied);
            $destination = $this->generateUniqueCopyPath($source);

            try {
                if (File::isDirectory($source)) {
                    File::copyDirectory($source, $destination);
                } else {
                    File::copy($source, $destination);
                }

                Notification::make()
                    ->title('Successfully pasted ' . (File::isDirectory($source) ? 'folder' : 'file'))
                    ->success()
                    ->send();

                $this->refresh(); // Refresh the file list
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error while pasting: ' . $e->getMessage())
                    ->danger()
                    ->send();
            }
        }

        private function generateUniqueCopyPath($source)
        {
            $sourceDirectory = dirname($source);
            $destinationDirectory = $this->storage($this->disk)->path($this->folder);

            // If the selected file is a folder, add it to the destination
            if ($this->selectedFile && $this->selectedFile['type'] == 'folder') {
                $destinationDirectory = $this->stripDoubleSlashesFromString($destinationDirectory . '/' . $this->selectedFile['name']);
            }

            $filename = pathinfo($source, PATHINFO_FILENAME);
            $extension = pathinfo($source, PATHINFO_EXTENSION);

            // If the source and destination directories are the same, start with "copy"
            if ($sourceDirectory === $destinationDirectory) {
                $newFilename = $filename . ' copy';
            } else {
                $newFilename = $filename;
            }

            $counter = 2;

            while (true) {
                $destination = $this->stripDoubleSlashesFromString($destinationDirectory . '/' . $newFilename . ($extension ? '.' . $extension : ''));
                
                if (!File::exists($destination)) {
                    return $destination;
                }

                // If we've reached this point, the file exists in the destination folder
                // So we need to append "copy" if it hasn't been done yet, or increment the counter
                if ($newFilename === $filename) {
                    $newFilename = $filename . ' copy';
                } else {
                    $newFilename = $filename . ' copy ' . $counter;
                    $counter++;
                }
            }
        }

        public function rename(){
            $this->renameFile($this->selectedFile['original_name'], $this->selectedFile['name']);
            $this->loadFilesInCurrentFolder();
        }

        public function renameFile(string $currentPath, string $newName)
        {
            // Construct the full current path
            $fullCurrentPath = $this->stripDoubleSlashesFromString($this->folder . '/' . $currentPath);
            
            // Get the directory of the current file
            $directory = dirname($fullCurrentPath);
            
            // Get the current file's information
            $currentFileInfo = pathinfo($fullCurrentPath);
            $currentExtension = $currentFileInfo['extension'] ?? '';
            
            // Check if the new name already contains the extension
            $newFileInfo = pathinfo($newName);
            $newExtension = $newFileInfo['extension'] ?? '';
            
            // Determine the final new name
            if ($newExtension && strtolower($newExtension) === strtolower($currentExtension)) {
                // If the new name already has the correct extension, use it as is
                $finalNewName = $newName;
            } else {
                // If not, append the current extension
                $finalNewName = $newFileInfo['filename'] . ($currentExtension ? '.' . $currentExtension : '');
            }
            
            // Construct the new full path
            $newPath = $this->stripDoubleSlashesFromString($directory . '/' . $finalNewName);

            // Check if the new filename already exists
            if ($this->storage($this->disk)->exists($newPath) && $newPath != $fullCurrentPath) {
                Notification::make()
                    ->title('A file or folder with this name already exists')
                    ->danger()
                    ->send();

                return false;
            }

            // Attempt to rename the file
            try {
                $this->storage($this->disk)->move($fullCurrentPath, $newPath);

                $this->refresh();
                $selectedFileName = $this->selectedFile['name'];
                $this->js('window.dispatchEvent(new CustomEvent("set-active-file", { detail: { name: "' . $selectedFileName . '" }}))');
                return $newPath;
            } catch (\Exception $e) {
                Notification::make()
                    ->title('An error occurred while renaming the file: ' . $e->getMessage())
                    ->danger()
                    ->send();

                return false;
            }
        }

        public function moveSelectedFileIntoFolder($folder){
            $relative_path_to_folder = $folder['relative_path'];
            $sourcePath = $this->stripDoubleSlashesFromString($this->folder . '/' . $this->selectedFile['name']);
            $destinationPath = $this->stripDoubleSlashesFromString($relative_path_to_folder . '/' . $this->selectedFile['name']);

            if (Storage::disk($this->disk)->exists($sourcePath)) {
                Storage::disk($this->disk)->move($sourcePath, $destinationPath);
                $this->selectedFile = null; // Clear the selected file
                $this->refresh(); // Refresh the file list
            } else {
                Notification::make()
                    ->title('Failed to move file')
                    ->danger()
                    ->send();
            }
        }

        public function searchStorageForFile()
        {
            $search = $this->search;
            $minMatchLength = 2;
            $results = [];
            $search = strtolower($search); // Convert search term to lowercase for case-insensitive matching

            // Get all files in the storage directory and subdirectories
            $allFiles = Storage::allFiles();

            foreach ($allFiles as $file) {
                $fileName = strtolower(basename($file)); // Get the filename and convert to lowercase
                
                // Check if the filename contains a sequence of characters from the search term
                if (strlen($search) >= $minMatchLength && stripos($fileName, $search) !== false) {
                    
                    $results[] = [
                        
                        'filename' => basename($file),
                        'type' => Storage::mimeType($file) == 'dir' ? 'folder' : Storage::mimeType($file),
                        'url' => $this->storage($this->disk)->url($file),
                        'relative_path' => $file,
                        'size' => $this->formatSize(Storage::size($file)),
                        'last_modified' => \Carbon\Carbon::parse(Storage::lastModified($file))->diffForHumans(),
                        'match_score' => similar_text($search, $fileName) / strlen($search) * 100,
                    ];
                }
            }

            // Sort results by match score (highest first)
            usort($results, function($a, $b) {
                return $b['match_score'] <=> $a['match_score'];
            });

            if (count($results) > 10) {
                $results = array_slice($results, 0, 10);
            }

            $this->searchResults = $results;
        }

    public function render()
    {
        return view('wave::livewire.media.manager');
    }
}
