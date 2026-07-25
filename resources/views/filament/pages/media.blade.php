<?php

use Livewire\Component;

new class extends Component
{
    public $upload;
    public $uploadFile;
    public $folder = '/';
    public $storageURL = '';
    public $files;
    public $disk;
    public $breadcrumbs;

    public function mount($disk = 'public'){
        $this->storageURL = $this->storage($disk)->url('/');
        $this->disk = $disk;
        $this->loadFilesInCurrentFolder();
        $this->getBreadcrumbsProperty();
    }

    private function loadFilesInCurrentFolder(){
        $this->files = $this->getFilesInDir($this->folder);
    }

    public function storage($disk = false){
        $storageClass = get_class(\Illuminate\Support\Facades\Storage::getFacadeRoot());
        $classInstance = new $storageClass(app());
        if($disk) $classInstance = $classInstance->disk($disk);
        return $classInstance;
    }

    public function getBreadcrumbsProperty(){
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
                        'path'          => $this->storage($this->disk)->url($item['path']),
                        'relative_path' => $item['path'],
                        'size'          => $item['size'] ?? $item->fileSize(),
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

    public function save()
    {
        $this->validate([
            'upload' => 'image|max:1024',
        ]);

        $this->uploadFile = $this->upload->store('photos', 'public');
        $this->uploadFile = storage()->url($this->uploadFile);
    }

    public function goToDirectory($path){
        $this->folder = '/' . $path;
        $this->loadFilesInCurrentFolder();
    }

}
?>
<x-filament-panels::page>
        <div class="flex justify-start items-start p-5 w-full h-full bg-white rounded-xl border border-zinc-100">
            <div class="w-full h-full">
                <div x-data="{
                        active: '',
                        files: @entangle('files'),
                        storageURL: @entangle('storageURL'),
                        activeFileDrawer: true,
                        isActiveFile(file) {
                            return this.active.relative_path == file.relative_path;
                        },
                        activeFileSelected() {
                            if(this.active != '') {
                                return true;
                            }
                            return false;
                        }
                    }" class="flex flex-col justify-start items-center w-full h-full bg-white">

                    <div class="relative p-5 pb-0 w-full bg-white border-b border-zinc-200/70">
                        <div class="flex justify-between items-center w-full">
                            <div class="text-sm breadcrumbs">
                                <ul class="flex gap-2">
                                    <li><a wire:click="goToDirectory('')" class="cursor-pointer">Home</a></li>
                                    @foreach($breadcrumbs as $breadcrumb)
                                        <li><a wire:click="goToDirectory('{{ ltrim($breadcrumb->location, '/') }}')" class="cursor-pointer">{{ $breadcrumb->display }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-start items-start w-full h-full bg-white">
                        <div class="grid flex-1 grid-cols-2 gap-4 p-5 w-full md:grid-cols-4 lg:grid-cols-6">
                            <template x-for="file in files" :key="file.relative_path">
                                <div @click="active=file" class="flex flex-col justify-center items-center p-3 rounded-lg border cursor-pointer border-zinc-200 hover:bg-zinc-50">
                                    <span x-text="file.name" class="text-xs truncate"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    @if($uploadFile ?? false)
                    @endif

                </div>
            </div>
        </div>
</x-filament-panels::page>
