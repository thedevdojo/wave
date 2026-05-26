<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
</head>
<body x-data class="flex flex-col min-h-screen overflow-x-hidden @if($bodyClass ?? false){{ $bodyClass }}@endif" x-cloak data-marketing-layout>

    <x-marketing.elements.header />

    <main class="flex-grow overflow-x-hidden">
        {{ $slot }}
    </main>

    @livewire('notifications')
    @include('theme::partials.footer')
    @include('theme::partials.footer-scripts')
    {{ $javascript ?? '' }}
    <script>
        function syncThemeForLayout() {
            const isMarketing =
                document.body.hasAttribute('data-marketing-layout');

            document.documentElement.classList.toggle('dark', !isMarketing);
        }
        document.addEventListener('livewire:navigated', syncThemeForLayout);
        syncThemeForLayout();
    </script>
</body>
</html>
