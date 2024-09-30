<x-filament-panels::page>
    <x-filament::section class="w-full">

        <div class="relative w-full">
            @if(count($themes) < 1) 
                <x-empty-state description="No themes found in your theme folder" />
            @endif

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-3 md:grid-cols-2">
                @foreach($themes as $theme)
                    <div class="overflow-hidden border rounded-md border-neutral-200 dark:border-neutral-700">
                        <img class="relative" src="{{ url('wave/theme/image' ) }}/{{ $theme->folder }}">
                        <div class="flex items-center justify-between flex-shrink-0 w-full p-4 border-t border-neutral-200 dark:border-neutral-700">
                            <div class="relative flex flex-col">
                                <h4 class="font-semibold">{{ $theme->name }}</h4>
                                <p class="text-xs text-zinc-500">@if(isset($theme->version)){{ 'version ' . $theme->version }}@endif</p>
                            </div>
                            <div class="relative flex items-center space-x-1">
                                <button wire:click="deleteTheme('{{ $theme->folder }}')" wire:confirm="Are you sure you want to delete {{ $theme->name }}?" class="flex items-center justify-center w-8 h-8 border rounded-md border-zinc-200 dark:border-zinc-700 dark:hover:bg-zinc-800 hover:bg-zinc-200">
                                    <x-phosphor-trash-bold class="w-4 h-4 text-red-500" />
                                </button>
                            </div>
                        </div>
                        <div class="w-full p-4 pt-0">
                            @if($theme->active)
                                <div class="flex justify-center items-center px-2 py-1.5 space-x-1.5 w-full text-sm text-center text-white bg-blue-500 rounded">
                                    <x-phosphor-check-bold class="w-4 h-4 text-white" />
                                    <span>Active</span>
                                </div>
                            @else
                                <button wire:click="activate('{{ $theme->folder }}')" class="flex justify-center items-center px-2 py-1.5 space-x-1.5 w-full text-sm text-blue-500 rounded border border-neutral-200 dark:border-neutral-700 hover:text-white hover:bg-blue-500 hover:border-blue-600">
                                    <x-phosphor-power-bold class="w-4 h-4" />
                                    <span>Activate Theme</span>
                                </button>
                            @endif
                        </div>
                    </div>

                @endforeach
            </div>
        </div>

        <div class="px-4 py-3 mt-5 text-sm text-gray-600 border border-gray-200 rounded-md bg-gray-50 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700">
            Looking for more themes? <a href="https://devdojo.com/wave/themes" target="_blank" class="text-blue-500 underline">Click here</a> to view all the available themes for Wave.
        </div>

    </x-filament::section>
</x-filament-panels::page>
