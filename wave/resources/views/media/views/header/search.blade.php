<div x-on:click.outside="searchFocused=false" class="hidden relative items-center px-0 md:flex md:mx-auto md:max-w-3xl lg:mx-0 lg:max-w-none">
    <div class="w-full">
        <label for="search" class="sr-only">Search</label>
        <div class="relative">
            <div class="flex absolute inset-y-0 left-0 items-center pl-2.5 pointer-events-none">
                <svg wire:loading wire:target="searchStorageForFile" class="w-4 h-4 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <svg wire:loading.remove wire:target="searchStorageForFile" class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"></path></svg>
            </div>
            <input id="search" x-on:focus="searchFocused=true;" name="search" x-model="search" x-on:keyup="if(search.length >= 2){ $wire.searchStorageForFile() }" class="block py-1.5 pr-3 pl-8 w-full text-sm placeholder-gray-500 bg-white rounded-md border-0 ring-1 ring-gray-200 focus:text-gray-900 focus:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-300 sm:text-sm" placeholder="Search" type="search">
        </div>
    </div>
    
    <div x-show="search && search.length >= 2 && searchFocused" class="absolute top-0 z-40 w-full rounded-md ring-1 backdrop-blur-sm translate-y-9 ring-zinc-200 bg-white/50">
        
            <div x-show="searchResults" class="p-1 space-y-1">
                <template x-for="searchResult in searchResults" :key="searchResult.relative_path">
                    <div x-on:click="window.dispatchEvent(new CustomEvent('open-file-modal', { detail: { file: searchResult }}));" class="flex justify-between items-center px-3 py-1 w-full rounded-md cursor-pointer text-zinc-900 hover:text-white hover:bg-indigo-500" >
                        <p><strong x-text="searchResult ? searchResult.filename : ''"></strong></p>
                        <p class="text-xs" x-text="searchResult ? searchResult.relative_path : ''"></p>
                    </div>
                </template>
            </div>

            <p x-show="searchResults == '' || !searchResults" class="p-8 w-full text-sm font-medium text-center text-zinc-400">No Results found for "<span x-text="search"></span>"</p>
        
    </div>
</div>