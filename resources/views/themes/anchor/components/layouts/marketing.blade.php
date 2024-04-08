<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
</head>
<body class="flex flex-col min-h-screen @if(Request::is('/')){{ 'bg-white' }}@endif @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">

    @if(config('wave.demo') && Request::is('/'))
        @include('theme::partials.demo-header')
    @endif

    @include('theme::partials.header-marketing')

    <main class="overflow-x-hidden flex-grow pt-24">
        {{ $slot }}
    </main>

    @include('theme::partials.footer')
    @include('theme::partials.footer-scripts')

    {{ $javascript ?? '' }}

</body>
</html>
