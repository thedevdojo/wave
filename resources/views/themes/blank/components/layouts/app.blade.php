<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
</head>
<body class="flex min-h-screen overflow-x-hidden @if($bodyClass ?? false){{ $bodyClass }}@endif">

    <x-app.sidebar />

    <main class="p-7 ml-64 w-full h-full">
        {{ $slot }}
    </main>

    @livewire('notifications')
    @filamentScripts
    @livewireScripts
    @waveCheckout
    
    {{ $javascript ?? '' }}

</body>
</html>
