<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
</head>
<body class="flex flex-col min-h-screen overflow-x-hidden @if($bodyClass ?? false){{ $bodyClass }}@endif">

    <x-marketing.header />

    <main class="w-full h-full">
        {{ $slot }}
    </main>

    @livewire('notifications')
    @filamentScripts
    @livewireScripts
    @waveCheckout
    
    {{ $javascript ?? '' }}

</body>
</html>
