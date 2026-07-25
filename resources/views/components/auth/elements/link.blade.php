<a
    {{ $attributes->except('wire:navigate') }}
    @if(config('devdojo.auth.settings.include_wire_navigate', true)) wire:navigate @endif
>
{{ $slot }}
</a>