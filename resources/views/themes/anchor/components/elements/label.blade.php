@props([
    'for' => ''
])

<label for="{{ $for }}" {{ $attributes->merge(['class' => 'block text-sm font-medium leading-5 text-zinc-700']) }}>
    {{ $slot }}
</label>