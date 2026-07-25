<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
</head>
<body x-data class="flex flex-col min-h-screen overflow-x-hidden @if($bodyClass ?? false){{ $bodyClass }}@endif" x-cloak data-marketing-layout>

    <x-marketing.elements.header />

    <main class="grow overflow-x-hidden">
        {{ $slot }}
    </main>

    @livewire('notifications')
    @include('theme::partials.footer')
    @include('theme::partials.footer-scripts')
    {{ $javascript ?? '' }}
    @include('theme::partials.theme-sync')
</body>
</html>
