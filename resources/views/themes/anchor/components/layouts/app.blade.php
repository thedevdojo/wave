<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
    <!-- Used to add dark mode right away, adding here prevents any flicker -->
    <script>
        if (typeof(Storage) !== "undefined") {
            if(localStorage.getItem('theme') && localStorage.getItem('theme') == 'dark'){
                document.documentElement.classList.add('dark');
            }
        }
    </script>
</head>
<body class="flex flex-col min-h-screen bg-zinc-50 dark:bg-zinc-800 @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">

    @if(config('wave.demo') && Request::is('/'))
        @include('theme::partials.demo-header')
    @endif

    <x-app.sidebar />

    <div class="flex flex-col min-h-screen justify-stretch md:pl-64">
        <!-- Page Heading -->
        @if (isset($header))
            <header x-data="{ mobileMenu: false }" class="bg-transparent">
                <div class="flex items-center mx-auto h-14">
                    <div @click="window.dispatchEvent(new CustomEvent('open-sidebar'))" class="flex justify-center items-center mr-1.5 w-7 h-7 rounded cursor-pointer group hover:bg-slate-200 dark:hover:bg-zinc-700 dark:text-white md:hidden">
                        <button class="block h-full">
                            <div  class="flex flex-col justify-between items-start w-4 h-3 transition-all duration-300 linear">
                                <div class="h-[2px] flex-shrink-0 rounded-full transition-all linear duration-200 bg-zinc-500 group-hover:bg-zinc-700 dark:bg-zinc-300 dark:group-hover:bg-zinc-100 w-full group-hover:w-2/3 -translate-x-px"></div>
                                <div class="h-[2px] flex-shrink-0 rounded-full transition-all linear duration-200 bg-zinc-500 group-hover:bg-zinc-700 dark:bg-zinc-300 dark:group-hover:bg-zinc-100 opacity-100 w-1/2 group-hover:w-full -translate-x-px"></div>
                                <div class="h-[2px] flex-shrink-0 rounded-full transition-all linear duration-200 bg-zinc-500 group-hover:bg-zinc-700 dark:bg-zinc-300 dark:group-hover:bg-zinc-100 group-hover:w-1/2 w-2/3 -translate-x-px"></div>
                            </div>
                        </button>
                    </div>
                    {{ $header }}
                </div>
            </header>
        @endif
        
        {{ $slot }}
    </div>

    @livewire('notifications')
    @if(!auth()->guest() && auth()->user()->hasChangelogNotifications())
        @include('theme::partials.changelogs')
    @endif
    @include('theme::partials.footer-scripts')
    {{ $javascript ?? '' }}

</body>
</html>

