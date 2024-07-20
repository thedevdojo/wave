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
<body class="flex flex-col min-h-screen lg:px-0 px-10 bg-white dark:bg-zinc-950 @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">

    <a href="/wave" x-data="{ visible: false, iniFrame() {
        alert('coo');
    if (document.referrer !== '') {
        alert('k');
        // The page is in an iFrame
        document.write("The page is in an iFrame");
    } else {
        alert('no go');
        // The page is not in an iFrame
        document.write("The page is not in an iFrame");
    }
} }" x-init="iniFrame()" :class="{ 'opacity-100' : visible, 'opacity-0' : !visible }" class="absolute shadow-[0_1px_2px_0_rgb(0,0,0,0.05)] hover:shadow-none top-0 right-0 z-50 flex items-end text-gray-700 hover:text-gray-900 justify-center border-b border-gray-200/20 group w-32 h-32 rotate-45 translate-x-20 -translate-y-20 ease-out duration-300 cursor-pointer hover:translate-x-[4.9rem] hover:-translate-y-[4.9rem] bg-white hover:bg-gradient-to-b hover:from-gray-50 hover:to-white">
        <svg class="w-6 h-6 opacity-90 translate-x-0.5 -translate-y-1.5 fill-current group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v13.19l5.47-5.47a.75.75 0 111.06 1.06l-6.75 6.75a.75.75 0 01-1.06 0l-6.75-6.75a.75.75 0 111.06-1.06l5.47 5.47V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"></path></svg>
    </a>
    @if(config('wave.demo') && Request::is('/'))
        {{-- @include('theme::partials.demo-header') --}}
    @endif

    <x-app.sidebar />

    <div class="flex flex-col pl-0 min-h-screen justify-stretch lg:pl-64">
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

