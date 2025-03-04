<div x-data="{ sidebarOpen: false }"  @open-sidebar.window="sidebarOpen = true"
    x-init="
        $watch('sidebarOpen', function(value){
            if(value){ document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); }
        });
    "
    class="relative z-50 w-screen md:w-auto" x-cloak>
    {{-- Backdrop for mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed top-0 right-0 z-50 w-screen h-screen duration-300 ease-out bg-black/20 dark:bg-white/10"></div>
    
    {{-- Sidebar --}} 
    <div :class="{ '-translate-x-full': !sidebarOpen }"
        class="fixed top-0 left-0 flex items-stretch -translate-x-full overflow-hidden lg:translate-x-0 z-50 h-dvh md:h-screen transition-[width,transform] duration-150 ease-out bg-zinc-50 dark:bg-zinc-900 w-64 group @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">  
        <div class="flex flex-col justify-between w-full overflow-auto md:h-full h-svh pt-4 pb-2.5">
            <div class="relative flex flex-col">
                <button x-on:click="sidebarOpen=false" class="flex items-center justify-center flex-shrink-0 w-10 h-10 ml-4 rounded-md lg:hidden text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 dark:hover:bg-zinc-700/70 hover:bg-gray-200/70">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>

                <div class="flex items-center px-5 space-x-2">
                    <a href="/" class="flex justify-center items-center py-4 pl-0.5 space-x-1 font-bold text-zinc-900">
                        <x-logo class="w-auto h-7" />
                    </a>
                </div>

                <div class="flex flex-col justify-start items-center mt-4 px-4 space-y-1.5 w-full h-full text-slate-600 dark:text-zinc-400">
                    <x-app.sidebar-link href="/dashboard" icon="phosphor-house" :active="Request::is('dashboard')">Dashboard</x-app.sidebar-link>
                    <x-app.sidebar-link href="/generator" icon="phosphor-sparkle" :active="Request::is('generator')">Generator</x-app.sidebar-link>
                    <div class="relative w-full">
                        <x-app.sidebar-link href="/inspiration" icon="phosphor-lightbulb" :active="Request::is('inspiration')">Inspiration</x-app.sidebar-link>
                        <div class="absolute top-1/2 -translate-y-1/2 right-3">
                            <svg width="11" height="13" viewBox="0 0 11 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_31412_16155)">
                                    <path d="M0 2.0502V10.0502C0 11.1502 0.9 12.0502 2 12.0502H9C10.1 12.0502 11 11.1502 11 10.0502V2.0502L8 6.0502L5.5 0.950195L3 6.0502L0 2.0502Z" fill="#FCB709"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_31412_16155">
                                        <rect width="11" height="11.1" fill="white" transform="translate(0 0.950195)"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative px-2.5 space-y-1.5 text-zinc-700 dark:text-zinc-400">
                <div class="w-full h-px my-2 bg-slate-100 dark:bg-zinc-700"></div>
                
                {{-- Usage and Upgrade Block --}}
                <div class="p-3 bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700 dark:text-zinc-300">Usage</span>
                        <span class="text-sm text-slate-500 dark:text-zinc-400">
                            {{ auth()->user()->subscription?->plan->post_credits - auth()->user()->post_credits }}/{{ auth()->user()->subscription?->plan->post_credits ?? 100 }}
                        </span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 dark:bg-zinc-700 rounded-full overflow-hidden mb-3">
                        @php
                            $percentage = auth()->user()->subscription ? round(((auth()->user()->subscription->plan->post_credits - auth()->user()->post_credits) / (auth()->user()->subscription->plan->post_credits ?? 100) * 100)) : 0;
                        @endphp
                        <div class="h-full bg-blue-500 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                    </div>
                    <a href="{{ route('settings.subscription') }}" class="block w-full px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">
                        {{ auth()->user()->subscription ? 'Upgrade plan' : 'Choose a plan' }}
                    </a>
                </div>

                <x-app.user-menu />
            </div>
        </div>
    </div>
</div>
