@props([
    'for' => ''
])

<label for="{{ $for }}" {{ $attributes->merge(['class' => 'fi-fo-field-wrp-label inline-flex items-center gap-x-3']) }}>
    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
        {{ $slot }}
    </span>
</label>