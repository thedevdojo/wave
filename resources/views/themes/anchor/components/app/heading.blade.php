@props([
    'title' => '',
    'description' => '',
    'size' => 'sm',
    'border' => true
])

<div class="@if($border){{ 'pb-5 border-b border-gray-200' }}@endif">
    <h3 
        @class([
            'text-base font-semibold leading-6 text-gray-900 dark:text-zinc-100',
            'text-base' => $size == 'sm',
            'text-lg' => $size == 'md',
            'text-xl' => $size == 'lg'
        ])
    >{{ $title ?? '' }}</h3>
    <p 
        @class([
            'max-w-4xl text-zinc-500 dark:text-zinc-400',
            'text-sm mt-1.5' => $size == 'sm',
            'text-base mt-1.5' => $size == 'md',
            'text-base mt-2' => $size == 'lg'
        ])
    >{{ $description ?? '' }}</p>
</div>