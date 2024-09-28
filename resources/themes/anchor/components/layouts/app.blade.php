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
<body x-data class="flex flex-col lg:min-h-screen bg-zinc-50 dark:bg-zinc-900 @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">

    <x-app.sidebar />

    <div class="flex flex-col pl-0 min-h-screen justify-stretch lg:pl-64">
        {{-- Mobile Header --}}
        <header class="lg:hidden px-5 block flex justify-between sticky top-0 z-40 bg-gray-50 -mb-px border-b border-zinc-200/70 h-[72px] items-center">
            <button x-on:click="window.dispatchEvent(new CustomEvent('open-sidebar'))" class="h-10 w-10 flex items-center justify-center flex-shrink-0 hover:bg-gray-200/70 rounded-md">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
            </button>
            <x-app.user-menu position="top" />
        </header>
        {{-- End Mobile Header --}}
        <main class="xl:px-0 lg:pt-4 lg:h-screen flex-1 flex flex-col">
            <div class="bg-white dark:bg-zinc-800 bg-white flex-1 lg:rounded-tl-xl overflow-hidden border-l border-t border-zinc-200/70 dark:border-zinc-700 h-full">
                <div class="h-full w-full px-5 sm:px-8 lg:overflow-y-scroll scrollbar-hidden lg:pt-5 lg:px-5">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    @livewire('notifications')
    @if(!auth()->guest() && auth()->user()->hasChangelogNotifications())
        @include('theme::partials.changelogs')
    @endif
    @include('theme::partials.footer-scripts')
    {{ $javascript ?? '' }}
    

</body>
</html>

