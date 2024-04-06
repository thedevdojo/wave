<x-filament-panels::page>
    <x-filament::section class="w-full">
	
        <div class="relative w-full">
            @if(count($themes) < 1)
                <x-empty-state description="No themes found in your theme folder" />
            @endif

            <div class="grid grid-cols-3 gap-5">
                @foreach($themes as $theme)

                    <div class="overflow-hidden rounded-md border border-zinc-200">
                        <img class="relative" src="{{ url('wave/theme/image' ) }}/{{ $theme->folder }}">
                        <div class="flex flex-shrink-0 justify-between items-center p-4 w-full border-t border-zinc-200">
                            <div class="flex relative flex-col">
                                <h4 class="font-semibold">{{ $theme->name }}</h4>
                                <p class="text-xs text-zinc-500">@if(isset($theme->version)){{ 'version ' . $theme->version }}@endif</p>
                            </div>
                            <div class="flex relative items-center space-x-1">
                                <a href="/admin/themes/delete" class="flex justify-center items-center w-8 h-8 rounded-md border border-zinc-200 hover:bg-zinc-200" data-id="{{ $theme->id }}">
                                    <x-phosphor-gear-bold class="w-4 h-4 text-zinc-500" />
                                </a>
                                <a href="/admin/themes/delete" class="flex justify-center items-center w-8 h-8 rounded-md border border-zinc-200 hover:bg-zinc-200" data-id="{{ $theme->id }}">
                                    <x-phosphor-trash-bold class="w-4 h-4 text-red-500" />
                                </a>
                            </div>
                        </div>
                        <div class="p-4 pt-0 w-full">
                            @if($theme->active)
                                    <div class="flex justify-center items-center px-2 py-1 space-x-1.5 h-8 text-center text-white bg-blue-500 rounded-md">
                                        <x-phosphor-check-bold class="w-4 h-4 text-white" /> 
                                        <span>Active</span>
                                    </div>
                                @else
                                    <a class="flex justify-center items-center px-2 py-1 space-x-1.5 text-blue-500 rounded-md border border-zinc-200 hover:bg-blue-500 hover:border-blue-600 hover:text-white" href="/theme/activate">
                                        <x-phosphor-power-bold class="w-4 h-4" />
                                        <span>Activate Theme</span>
                                    </a>
                                @endif
                            </div>
                    </div>

                @endforeach
            </div>
        </div>

    </x-filament::section>
</x-filament-panels::page>
