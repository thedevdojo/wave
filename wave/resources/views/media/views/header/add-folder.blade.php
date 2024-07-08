<x-filament::modal id="create-folder-modal" width="lg">
    <x-slot name="trigger">
        <button class="flex items-center px-4 py-2 text-xs font-medium text-white bg-black outline-none focus:outline-none hover:bg-gray-900">
            <svg class="mr-1 w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M21.25 11.25v-1.5a3 3 0 0 0-3-3h-3.077a3 3 0 0 1-2.035-.796l-1.526-1.408a3 3 0 0 0-2.035-.796H5.749a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h5.501" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M18 21.25v-6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M14.75 18h6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
            <span class="hidden lg:inline">Add Folder</span>
        </button>
    </x-slot>
    
    <x-slot name="heading">
        Folder Name
    </x-slot>
    
    <form wire:submit="createNewFolder">
        <x-filament::input.wrapper label="Folder Name">
            <x-filament::input autofocus type="text" id="folderName" label="folder name" wire:model="folderName" />
        </x-filament::input.wrapper>
        @error('folderName')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </form>
    
    <x-slot name="footer">
        <x-filament::button type="submit" wire:click="createNewFolder">
            Create Folder
        </x-filament::button>
    </x-slot>
</x-filament::modal>