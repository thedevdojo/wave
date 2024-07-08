<x-filament::modal id="move-file-modal" width="lg">
    <x-slot name="trigger">
        <button class="flex items-center px-4 py-2 text-xs font-medium text-gray-700 bg-white hover:bg-gray-100">
            <svg class="mr-1 w-4 h-4 -translate-y-px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M20.25 7h-9.875a6.625 6.625 0 1 0 0 13.25h5.875m4-13.25l-3.5 3.25M20.25 7l-3.5-3.25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
            <span class="hidden xl:inline">Move</span>
        </button>
    </x-slot>
    
    <x-slot name="heading">
        Move to Folder
    </x-slot>
    
    <form wire:submit="moveFileOrFolder">
        <x-filament::input.wrapper>
            <x-filament::input.select
                wire:model="destinationFolder"
                label="Select Destination Folder"
                placeholder="Select a folder"
            >
                <option value="" disabled selected>Select a folder</option>
                @if(!$this->isRootDirectory())
                    <option value=".." selected>Previous Folder (../)</option>
                @endif
                @foreach($this->getFoldersInCurrentDirectory() as $folder)
                    @if($selectedFile != null && $selectedFile['name'] != $folder)
                        <option value="{{ $folder }}">{{ $folder }}</option>
                    @endif
                @endforeach
            </x-filament::input.select>
        </x-filament::input.wrapper>
    </form>
    
    <x-slot name="footer">
        <x-filament::button type="submit" wire:click="moveFileOrFolder">
            Move
        </x-filament::button>
    </x-slot>
</x-filament::modal>