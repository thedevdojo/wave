@props([
    'value' => ''
])

<div data-auth="email-read-only-placeholder" {{ $attributes->merge(['class' => 'px-3.5 bg-gray-50 py-2.5 text-sm flex items-center justify-between border rounded-md border-gray-300']) }}>
    <span>{{ $value }}</span>
    {{ $slot }}
</div>