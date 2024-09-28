@props([
    'href' => '',
    'icon' => 'phosphor-house-duotone',
    'active' => false,
    'hideUntilGroupHover' => true,
    'target' => '_self',
    'ajax' => true
])

@php
    $isActive = filter_var($active, FILTER_VALIDATE_BOOLEAN);
@endphp

<a {{ $attributes }} href="{{ $href }}" @if((($href ?? false) && $target == '_self') && $ajax) wire:navigate @else @if($ajax) target="_blank" @endif @endif class="@if($isActive){{ 'text-zinc-900 border-zinc-200 dark:border-zinc-700 shadow-sm bg-white font-medium dark:border-white dark:bg-zinc-700/60 dark:text-zinc-100' }}@else{{ 'border-transparent' }}@endif transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2 overflow-hidden group-hover:autoflow-auto items">
    <x-dynamic-component :component="$icon" class="flex-shrink-0 w-5 h-5" />
    <span class="flex-shrink-0 ease-out duration-50">{{ $slot }}</span>
</a>
