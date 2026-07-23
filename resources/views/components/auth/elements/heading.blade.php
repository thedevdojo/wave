@props([
    'align' => 'center',
    'text' => 'Heading Text',
    'description' => '',
    'show_subheadline' => false
])

@php
    $heading_alignment = config('devdojo.auth.appearance.alignment.heading');
@endphp

<div @class([
        'flex flex-col sm:mx-auto sm:w-full mb-5 sm:max-w-md',
        'items-start' => $heading_alignment == 'left',
        'items-center' => $heading_alignment == 'center',
        'items-end' => $heading_alignment == 'right'
        
    ])
    id="auth-heading-container"
    style="color:{{ config('devdojo.auth.appearance.color.text') }}"
    >
    <div @class([
        'flex flex-col w-full',
        'items-start' => $heading_alignment == 'left',
        'items-center' => $heading_alignment == 'center',
        'items-end' => $heading_alignment == 'right',
    ])
>
        <x-auth::elements.logo
         :height="config('devdojo.auth.appearance.logo.height')"
         :isImage="(config('devdojo.auth.appearance.logo.type') == 'image')"
         :imageSrc="config('devdojo.auth.appearance.logo.image_src')"
         :svgString="config('devdojo.auth.appearance.logo.svg_string')"
         />
    </div>
    <h1 id="auth-heading-title" class="mt-1 text-xl font-medium leading-9">{{ $text ?? '' }}</h1>
    @if(($description ?? false) && $show_subheadline)
        <p id="auth-heading-description" class="mb-1.5 space-x-0.5 text-sm leading-5 text-center opacity-67">{{ $description ?? '' }}</p>
    @endif
</div>