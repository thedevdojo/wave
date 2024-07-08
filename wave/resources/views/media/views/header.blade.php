<header class="w-full">
    <div class="flex space-x-3 w-full">
        <div class="flex overflow-hidden flex-shrink-0 rounded divide-x divide-gray-700">
            <div class="relative" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                <button class="flex relative items-center px-4 py-2 text-xs font-medium text-white bg-black hover:bg-gray-900">
                    <label class="absolute inset-0 w-full h-full cursor-pointer">
                        <input type="file" wire:model="upload" id="upload-button" class="hidden absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </label>
                    <svg class="w-4 h-4 md:mr-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M12.0005 11.7495L12.0005 20.2495M12.0005 11.7495L15.2505 15.2495M12.0005 11.7495L8.75049 15.2495" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M4.25 15.25C2.96461 14.2882 2.75 13.1762 2.75 12C2.75 9.94957 4.20204 8.23828 6.13392 7.83831C7.01365 5.45184 9.30808 3.75 12 3.75C15.3711 3.75 18.1189 6.41898 18.2454 9.75913C19.9257 9.8846 21.25 11.2876 21.25 13C21.25 14.0407 20.5 15 19.75 15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                    <span class="hidden md:inline">Upload</span>
                </button>
            </div>
            @include('wave::media.views.header.add-folder')
        </div>

        <button wire:click="refresh" class="flex-shrink-0 p-2 text-gray-700 bg-white rounded ring-1 ring-gray-200 hover:bg-gray-100">
            <svg wire:target="refresh" wire:loading.class="animate-spin" class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M7.73535 6.13705C8.97295 5.23681 10.4637 4.75128 11.9941 4.75C13.5245 4.74872 15.016 5.23176 16.2551 6.12994C17.4942 7.02812 18.4173 8.29536 18.8922 9.75021C19.3604 11.1844 19.3693 12.7283 18.9187 14.167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path><path d="M16.2648 17.8629C15.0272 18.7631 13.5364 19.2487 12.0061 19.2499C10.4757 19.2512 8.98412 18.7682 7.74502 17.87C6.50591 16.9718 5.58281 15.7046 5.10791 14.2497C4.63963 12.8152 4.63081 11.2709 5.08176 9.83191" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path><path d="M2.75 12.252L5 9.75L7.25 12.252" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.75 11.75L18.9996 14.25L21.25 11.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
        </button>

        <div class="flex-1">
            @include('wave::media.views.header.search')
        </div>

        <div class="flex overflow-hidden flex-shrink-0 rounded divide-x divide-gray-200 ring-1 ring-gray-200">
            @include('wave::media.views.header.move')
            {{-- @include('wave::media.views.header.delete') --}}
            {{ $this->deleteAction }}
        </div>

        <div class="flex overflow-hidden flex-shrink-0 rounded divide-x divide-gray-200 ring-1 ring-gray-200">
            <button x-on:click="view='list'" :class="{ 'bg-gray-100' : view == 'list', 'bg-white hover:bg-gray-100' : view != 'list' }" class="flex items-center px-4 py-2 text-xs font-medium text-gray-700">
                <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.75 5h1.5m-1.5 7h1.5m-1.5 7h1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.75 5h11.5M8.75 19h11.5m-11.5-7h11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
            </button>
            <button x-on:click="view='grid'" :class="{ 'bg-gray-100' : view == 'grid', 'bg-white hover:bg-gray-100' : view != 'grid' }" class="px-4 font-medium text-xs py-2 text-gray0=-700 flex items-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.75 5.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.705 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.705-1.575-1.576V5.326z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.75 15.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.706 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.706-1.575-1.576v-3.348z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.75 5.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.705 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.705-1.575-1.576V5.326z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.75 15.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.706 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.706-1.575-1.576v-3.348z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
            </button>
        </div>
    </div>
    <!-- Progress Bar -->
    <div x-show="isUploading" class="overflow-hidden relative top-0 right-0 left-0 mt-2 h-2 rounded-full bg-zinc-100">
        <span class="block absolute top-0 left-0 h-full bg-green-500" :style="`width: ${progress}%`"></span>
    </div>
    @error('upload')
        <div x-data="{ visible: true }" x-show="visible" class="flex relative justify-between items-center px-4 py-2.5 mt-2 w-full text-sm text-red-800 bg-red-100 rounded-md">
            <span>{{ $message }}</span>
            <span x-on:click="visible=false" class="flex justify-center items-center w-5 h-5 rounded-full translate-x-1.5 cursor-pointer hover:bg-red-200 leading-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </span>
    @enderror
</header>