@props([
    'prefixText' => '',
    'suffixText' => '',
    'prefixIcon' => '',
    'prefixIconColor' => '',
    'suffixIcon' => '',
    'affixIconColor' => '',
    'valid' => true
])

<x-filament::input.wrapper
    :valid="$valid"
    :prefix-icon="$prefixIcon" 
    :prefix-icon-color="$prefixIconColor" 
    :suffixIcon="$suffixIcon" 
    :affix-icon-color="$affixIconColor"
    label="cool beans"
>
    @if ($prefixText)
        <x-slot name="prefix">{{ $prefixText }}</x-slot>
    @endif
    <x-filament::input
        type="text"
        {{ $attributes }}
    />
    @if ($suffixText)
        <x-slot name="suffix">{{ $suffixText }}</x-slot>
    @endif
</x-filament::input.wrapper>