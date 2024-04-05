<x-filament-panels::page>
    <x-filament::section class="w-full">
	
        <div class="relative w-full">
            @if(count($themes) < 1)
                <x-empty-state description="No themes found in your theme folder" />
            @endif

            <div class="grid grid-cols-3">
                @foreach($themes as $theme)

                    <div class="col-span-1">
                        <img class="relative" src="{{ url('themes' ) }}/{{ $theme->folder }}/{{ $theme->folder }}.jpg">
                        <div class="flex">
                            <h4>{{ $theme->name }}<span>@if(isset($theme->version)){{ 'version ' . $theme->version }}@endif</span></h4>
                            @if($theme->active)
                                <span class="btn btn-success pull-right"><i class="voyager-check"></i> Active</span>
                            @else
                                <a class="btn btn-outline pull-right" href="{{ route('voyager.theme.activate', $theme->folder) }}"><i class="voyager-check"></i> Activate Theme</a>
                            @endif
                            <a href="/admin/themes/{{ $theme->folder }}" class="voyager-params theme-options"></a>
                            <div class="voyager-trash theme-options-trash" data-id="{{ $theme->id }}"></div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>

    </x-filament::section>
</x-filament-panels::page>
