@include('wave::premium-theme-message')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
</head>
<body class="flex flex-col min-h-screen overflow-x-hidden bg-white dark:bg-black @if($bodyClass ?? false){{ $bodyClass }}@endif">

    <x-marketing.elements.header />

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <x-marketing.elements.footer />

    @livewire('notifications')
    @filamentScripts
    @livewireScripts
    
    {{ $javascript ?? '' }}

</body>
</html>