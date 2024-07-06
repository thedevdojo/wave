<?php
    use function Laravel\Folio\{name};
    use Livewire\Volt\Component;
    name('media');

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
            // We want to get the class from the Storage facade, this is probably Illuminate\Filesystem\FilesystemManager
            $storageClass = get_class(\Illuminate\Support\Facades\Storage::getFacadeRoot());

            // create a new instance of this object to be used
            $classInstance = new $storageClass(app());

            // if the disk is set by default return the disk passed in
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
                'upload' => 'image|max:1024', // 1MB Max
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
    @volt('media')
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
                    
                    <header class="flex space-x-3 w-full">
                        <div class="flex overflow-hidden flex-shrink-0 rounded divide-x divide-gray-700">
                            <button class="flex relative items-center px-4 py-2 text-xs font-medium text-white bg-black hover:bg-gray-900">
                                <label class="absolute inset-0 w-full h-full cursor-pointer">
                                    <input type="file" wire:model="upload" class="hidden absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </label>
                                <svg class="mr-1 w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M12.0005 11.7495L12.0005 20.2495M12.0005 11.7495L15.2505 15.2495M12.0005 11.7495L8.75049 15.2495" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M4.25 15.25C2.96461 14.2882 2.75 13.1762 2.75 12C2.75 9.94957 4.20204 8.23828 6.13392 7.83831C7.01365 5.45184 9.30808 3.75 12 3.75C15.3711 3.75 18.1189 6.41898 18.2454 9.75913C19.9257 9.8846 21.25 11.2876 21.25 13C21.25 14.0407 20.5 15 19.75 15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                <span>Upload</span>
                            </button>
                            <button class="flex items-center px-4 py-2 text-xs font-medium text-white bg-black hover:bg-gray-900">
                                <svg class="mr-1 w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M21.25 11.25v-1.5a3 3 0 0 0-3-3h-3.077a3 3 0 0 1-2.035-.796l-1.526-1.408a3 3 0 0 0-2.035-.796H5.749a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h5.501" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M18 21.25v-6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M14.75 18h6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                <span>Add Folder</span>
                            </button>
                        </div>

                        <button class="flex-shrink-0 p-2 text-gray-700 bg-white rounded ring-1 ring-gray-200 hover:bg-gray-100">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M7.73535 6.13705C8.97295 5.23681 10.4637 4.75128 11.9941 4.75C13.5245 4.74872 15.016 5.23176 16.2551 6.12994C17.4942 7.02812 18.4173 8.29536 18.8922 9.75021C19.3604 11.1844 19.3693 12.7283 18.9187 14.167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path><path d="M16.2648 17.8629C15.0272 18.7631 13.5364 19.2487 12.0061 19.2499C10.4757 19.2512 8.98412 18.7682 7.74502 17.87C6.50591 16.9718 5.58281 15.7046 5.10791 14.2497C4.63963 12.8152 4.63081 11.2709 5.08176 9.83191" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path><path d="M2.75 12.252L5 9.75L7.25 12.252" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.75 11.75L18.9996 14.25L21.25 11.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                        </button>

                        <div class="flex-1">
                            <div class="flex items-center px-6 md:mx-auto md:max-w-3xl lg:mx-0 lg:max-w-none xl:px-0">
                                <div class="w-full">
                                    <label for="search" class="sr-only">Search</label>
                                    <div class="relative">
                                        <div class="flex absolute inset-y-0 left-0 items-center pl-2.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <input id="search" name="search" class="block py-1.5 pr-3 pl-8 w-full text-sm placeholder-gray-500 bg-white rounded-md border-0 ring-1 ring-gray-200 focus:text-gray-900 focus:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-300 sm:text-sm" placeholder="Search" type="search">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex overflow-hidden flex-shrink-0 rounded divide-x divide-gray-200 ring-1 ring-gray-200">
                            <button class="flex items-center px-4 py-2 text-xs font-medium text-gray-700 bg-white hover:bg-gray-100">
                                <svg class="mr-1 w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M16.89 20.25H5.75a3 3 0 0 1-3-3V6.75a3 3 0 0 1 3-3H7.9a3 3 0 0 1 1.573.445l1.804 1.11a3 3 0 0 0 1.572.445h2.402a3 3 0 0 1 3 3v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.89 20.25a3 3 0 0 0 2.916-2.294l1.39-5.735a2 2 0 0 0-1.944-2.471h-9.24a3 3 0 0 0-2.885 2.176l-2.343 8.166" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                <span>Move</span>
                            </button>
                            <button class="flex items-center px-4 py-2 text-xs font-medium text-red-500 bg-white hover:bg-gray-100">
                                <svg class="mr-1 w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M12 21.25c5.244 0 5.75-.128 6.25-6.25.242-2.966.428-4.986.381-6.36A.374.374 0 0 1 19 8.25a1.25 1.25 0 1 0 0-2.5h-3.548c-.9-.029-2.034 0-3.452 0-1.418 0-2.552-.029-3.452 0H5a1.25 1.25 0 1 0 0 2.5c.21 0 .376.179.369.39-.047 1.374.139 3.394.381 6.36.5 6.122 1.006 6.25 6.25 6.25z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7.085 5.75l1.094-1.97a2 2 0 0 1 1.748-1.03h4.146a2 2 0 0 1 1.748 1.03l1.094 1.97" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9.75 8.75v8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M14.25 8.75v8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                <span>Delete</span>
                            </button>
                        </div>

                        <div class="flex overflow-hidden flex-shrink-0 rounded divide-x divide-gray-200 ring-1 ring-gray-200">
                            <button class="flex items-center px-4 py-2 text-xs font-medium text-gray-700 bg-white hover:bg-gray-100">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.75 5h1.5m-1.5 7h1.5m-1.5 7h1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.75 5h11.5M8.75 19h11.5m-11.5-7h11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                            </button>
                            <button class="bg-white hover:bg-gray-100 px-4 font-medium text-xs py-2 text-gray0=-700 flex items-center">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.75 5.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.705 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.705-1.575-1.576V5.326z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.75 15.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.706 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.706-1.575-1.576v-3.348z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.75 5.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.705 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.705-1.575-1.576V5.326z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.75 15.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.706 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.706-1.575-1.576v-3.348z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                            </button>
                        </div>
                    </header>
                    
                    <nav class="flex justify-between items-center py-2 w-full text-xs">
                        <ol role="list" class="flex items-center space-x-1">
                            <li>
                                <button wire:click="goToDirectory('/')" class="inline-flex items-center px-3 py-2 font-normal text-center text-gray-900 bg-white rounded-md hover:bg-gray-100 focus:outline-none">
                                    <span>Home</span>
                                </button>
                            </li>
                            @foreach($breadcrumbs as $breadcrumb)
                                <li><svg class="flex-shrink-0 w-5 h-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"></path></svg></li>
                                <li>
                                    <button 
                                        wire:click="goToDirectory('{{ $breadcrumb->location }}')" 
                                        class="@if($loop->last) text-gray-400 @else text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:ring-2 focus:outline-none focus:ring-gray-100 @endif
                                            inline-flex items-center px-3 py-2 font-normal text-center  bg-white rounded-md">
                                        {{ $breadcrumb->display }}
                                    </button>
                                </li>
                            @endforeach
                        </ol>
                        <div class="flex items-center h-full">
                            <button @click="activeFileDrawer=!activeFileDrawer" class="px-2 h-full rounded-md hover:bg-gray-100">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M2.74902 6.75C2.74902 5.09315 4.09217 3.75 5.74902 3.75H18.2507C19.9075 3.75 21.2507 5.09315 21.2507 6.75V17.25C21.2507 18.9069 19.9075 20.25 18.2507 20.25H5.74902C4.09217 20.25 2.74902 18.9069 2.74902 17.25V6.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.75 3.75V20.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.75 7.75L18.25 7.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.75 11L18.25 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.75 14.25L18.25 14.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                            </button>
                        </div>
                    </nav>

                    <div class="flex justify-start items-stretch space-x-10 w-full h-full">
                        
                        <files class="flex w-full h-100">
                            <div class="overflow-hidden p-1 w-full h-full h-100">
                                <ul 
                                    :class="{ 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5' : activeFileDrawer, 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6' : !activeFileDrawer }"
                                    class="grid gap-x-4 gap-y-8 sm:gap-x-6 xl:gap-x-8">
                                    <template x-for="file in files" :key="file.relative_path">
                                        <li class="relative aspect-video" x-on:click="active=file" x-on:dblclick="$wire.goToDirectory(file.relative_path)">
                                            <div 
                                                :class="{ 'ring-indigo-500': isActiveFile(file), 'ring-gray-200' : !isActiveFile(file) }"
                                                class="flex overflow-hidden justify-center items-center w-full rounded-lg ring-2 ring-offset-2 group aspect-video">
                                                <template x-if="file.type == 'folder'">
                                                    <svg class="w-20 h-20 fill-current text-[#fbd775]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 00-3-3h-3.879a.75.75 0 01-.53-.22L11.47 3.66A2.25 2.25 0 009.879 3H6a3 3 0 00-3 3v3.162A3.756 3.756 0 014.094 9h15.812zM4.094 10.5a2.25 2.25 0 00-2.227 2.568l.857 6A2.25 2.25 0 004.951 21H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-2.227-2.568H4.094z" /></svg>
                                                </template>

                                                <template x-if="file.type.startsWith('image')">
                                                    <img :src="storageURL + file.relative_path" class="object-cover w-full h-auto pointer-events-none" />
                                                </template>

                                                <template x-if="!file.type.startsWith('image') && file.type != 'folder'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12"><path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" /><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" /></svg>
                                                </template>
                                            
                                                <button type="button" class="absolute inset-0 focus:outline-none">
                                                    <span class="sr-only" x-text="file.name"></span>
                                                </button>
                                            </div>
                                            <p 
                                                :class="{ 'text-indigo-600': isActiveFile(file), 'text-neutral-700' : !isActiveFile(file) }"
                                            class="block mt-2 text-sm font-medium truncate pointer-events-none" x-text="file.name"></p>
                                            <p class="block text-sm font-medium text-gray-500 pointer-events-none">3.9 MB</p>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </files>
                        
                        <active-file x-show="activeFileDrawer" class="relative flex-shrink-0 w-[17rem] h-full  bg-gray-50 rounded-md p-3">
                            <template x-if="!activeFileSelected()">
                                <div class="flex justify-center items-center h-32 text-sm text-gray-500">
                                    <p>No active file selected</p>
                                </div>
                            </template>

                            <template x-if="activeFileSelected()">
                                <div class="w-full h-auto">
                                    <div class="flex justify-center items-center p-3 w-full h-auto border-b border-neutral-200">
                                        <template x-if="active.type == 'folder'">
                                            <div class="flex justify-center items-center h-32">
                                                <svg class="w-20 h-20 fill-current text-[#fbd775]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 00-3-3h-3.879a.75.75 0 01-.53-.22L11.47 3.66A2.25 2.25 0 009.879 3H6a3 3 0 00-3 3v3.162A3.756 3.756 0 014.094 9h15.812zM4.094 10.5a2.25 2.25 0 00-2.227 2.568l.857 6A2.25 2.25 0 004.951 21H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-2.227-2.568H4.094z" /></svg>
                                            </div>
                                        </template>

                                        <template x-if="active.type.startsWith('image')">
                                            <img :src="storageURL + active.relative_path" class="object-cover w-full h-auto rounded pointer-events-none" />
                                        </template>

                                        <template x-if="!active.type.startsWith('image') && active.type != 'folder'">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12"><path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" /><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" /></svg>
                                        </template>
                                    </div>
                                    <div class="p-2 space-y-2 h-auto text-xs break-all text-neutral-400">
                                        <p><strong class="text-neutral-500">Filename:</strong> <span x-text="active.name"></span></p>
                                        <p><strong class="text-neutral-500">Type:</strong> <span x-text="active.type"></span></p>
                                        <p><strong class="text-neutral-500">Filesize:</strong> <span x-text="active.size"></span></p>
                                        <p><strong class="text-neutral-500">Full URL:</strong> <a :href="active.path" target="_blank" class="text-blue-300 underline hover:text-blue-400">open in new tab</a></p>
                                        <p><strong class="text-neutral-500">Last Modified:</strong> <span x-text="active.last_modified"></span></p>
                                    </div>
                                </div>
                            </template>
                        </active-file>
                    </div>

                    {{-- <div click="save">Save</div> --}}
                    @if($uploadFile ?? false)
                        {{-- <div class="overflow-hidden max-w-xs rounded-xl">
                            <img src="{{ $uploadFile }}" />
                        </div> --}}
                    @endif

                </div>
            </div>
        </div>
    @endvolt

</x-filament-panels::page>
