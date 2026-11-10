@php
    if(isset($seo)){
        $seo = (is_array($seo)) ? ((object)$seo) : $seo;
    }
@endphp
@if(isset($seo->title))
    <title>{{ $seo->title }}</title>
@else
    <title>{{ setting('site.title', 'Laravel Wave') . ' - ' . setting('site.description', 'The Software as a Service Starter Kit built with Laravel') }}</title>
@endif

<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge"> <!-- † -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('/') }}">

<x-favicon></x-favicon>

{{-- Social Share Open Graph Meta Tags --}}
@if(isset($seo->title) && isset($seo->description))
    <meta property="og:title" content="{{ $seo->title }}">
    <meta property="og:url" content="{{ Request::url() }}">
    @if(isset($seo->image))
        <meta property="og:image" content="{{ $seo->image }}">
    @endif
    <meta property="og:type" content="@if(isset($seo->type)){{ $seo->type }}@else{{ 'article' }}@endif">
    <meta property="og:description" content="{{ $seo->description }}">
    <meta property="og:site_name" content="{{ setting('site.title') }}">

    <meta itemprop="name" content="{{ $seo->title }}">
    <meta itemprop="description" content="{{ $seo->description }}">
    @if(isset($seo->image))
        <meta itemprop="image" content="{{ $seo->image }}">
    @endif

    @if(isset($seo->image) && isset($seo->image_w) && isset($seo->image_h))
        <meta property="og:image:width" content="{{ $seo->image_w }}">
        <meta property="og:image:height" content="{{ $seo->image_h }}">
    @endif
@endif

<meta name="robots" content="{{ $seo->robots ?? 'index,follow' }}">
<meta name="googlebot" content="{{ $seo->robots ?? 'index,follow' }}">

@if(isset($seo->description))
    <meta name="description" content="{{ $seo->description }}">
@endif

@filamentStyles
@livewireStyles
@vite(['resources/themes/anchor/assets/css/app.css', 'resources/themes/anchor/assets/js/app.js'])
