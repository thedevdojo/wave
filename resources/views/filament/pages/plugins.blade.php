<x-filament-panels::page>
    <x-filament::section class="w-full">
        <div class="relative w-full">
            @if(count($plugins) < 1) 
                <x-empty-state description="No plugins found in your plugins folder" />
            @endif

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-3 md:grid-cols-2">
                @foreach($plugins as $pluginFolder => $plugin)
                    <div class="overflow-hidden border rounded-md border-neutral-200 dark:border-neutral-700">
                        <img class="relative" src="{{ url('wave/plugin/image' ) }}/{{ $pluginFolder }}">
                        <div class="flex items-center justify-between flex-shrink-0 w-full p-4 border-b border-neutral-200 dark:border-neutral-700">
                            <div class="relative flex flex-col pr-3">
                                <h4 class="font-semibold">{{ $plugin['name'] }}</h4>
                                <p class="text-xs text-zinc-500">{{ $plugin['description'] }}</p>
                                <p class="text-xs text-zinc-500">{{ 'Version ' . ($plugin['version']['version'] ?? '') }}</p>
                            </div>
                            <div class="relative flex items-center space-x-1">
                                <button wire:click="deletePlugin('{{ $pluginFolder }}')" wire:confirm="Are you sure you want to delete {{ $plugin['name'] }}?" class="flex items-center justify-center w-8 h-8 border rounded-md border-zinc-200 dark:border-zinc-700 dark:hover:bg-zinc-800 hover:bg-zinc-200">
                                    <x-phosphor-trash-bold class="w-4 h-4 text-red-500" />
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center w-full p-4 space-x-2">
                            @if($plugin['active'])
                                <div class="flex justify-center items-center px-2 py-1.5 space-x-1.5 w-full text-sm text-center text-white bg-blue-500 rounded">
                                    <x-phosphor-check-bold class="w-4 h-4 text-white" />
                                    <span>Active</span>
                                </div>
                                <button wire:click="deactivate('{{ $pluginFolder }}')" class="flex justify-center items-center px-2 py-1.5 space-x-1.5 w-full text-sm text-red-500 hover:bg-red-500 rounded border border-neutral-200 dark:border-neutral-700 hover:text-white hover:border-red-600">
                                    <x-phosphor-power-bold class="w-4 h-4" />
                                    <span>Deactivate</span>
                                </button>
                            @else
                                <button wire:click="activate('{{ $pluginFolder }}')" class="flex justify-center items-center px-2 py-1.5 space-x-1.5 w-full text-sm text-blue-500 rounded border border-neutral-200 dark:border-neutral-700 hover:text-white hover:bg-blue-500 hover:border-blue-600">
                                    <x-phosphor-power-bold class="w-4 h-4" />
                                    <span>Activate</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>