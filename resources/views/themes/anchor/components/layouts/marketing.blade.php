<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
</head>
<body class="flex flex-col min-h-screen @if($bodyClass ?? false){{ $bodyClass }}@endif @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">

    @if(config('wave.demo') && Request::is('/'))
        @include('theme::partials.demo-header')
    @endif
    
    <x-marketing.elements.header />

    <main class="overflow-x-hidden flex-grow pt-24">
        {{ $slot }}
    </main>

    @livewire('notifications')
    @include('theme::partials.footer')
    @include('theme::partials.footer-scripts')

    {{ $javascript ?? '' }}

</body>
</html>
