<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="{{ config('app.name', 'Supapost') }}">

    <title>{{ $title ?? config('app.name', 'Supapost') }}</title>
    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">

    @if(isset($isArticle) && $isArticle)
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'Supapost') }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? '' }}">
    <meta property="og:description" content="{{ $description ?? '' }}">
    
    <meta itemprop="name" content="{{ $title ?? config('app.name', 'Supapost') }}">
    <meta itemprop="description" content="{{ $description ?? '' }}">
    <meta itemprop="image" content="{{ $ogImage ?? '' }}">
    
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    @endif
    
    <meta property="og:site_name" content="{{ config('app.name', 'Supapost') }} - X (Twitter) AI post generator">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/themes/anchor/assets/css/app.css', 'resources/themes/anchor/assets/js/app.js'])
    
    <!-- Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
</head>
<body class="font-sans antialiased h-full bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-app.sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ config('app.name', 'Supapost') }}
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    
    @yield('javascript')
</body>
</html> 