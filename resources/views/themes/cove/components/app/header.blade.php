<header x-data="{ mobileMenuOpen: false }" class="relative z-30 @if(Request::is('/')){{ 'bg-white' }}@else{{ 'bg-zinc-50' }}@endif">
    <div class="px-8 mx-auto max-w-7xl xl:px-5">
        <div class="flex relative z-30 justify-between items-center h-24 md:space-x-6">
            <div class="inline-flex">
            <!-- data-replace='{ "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }' -->
                <a href="{{ route('home') }}" class="flex justify-center items-center space-x-3 text-blue-500 transition-all duration-1000 ease-out transform">
                   <x-logo class="w-auto h-8"></x-logo>
                </a>
            </div>
            <div class="flex flex-grow justify-end -my-2 -mr-2 md:hidden">
                <button x-show="!mobileMenuOpen" @click="mobileMenuOpen = true" type="button" class="inline-flex justify-center items-center p-2 rounded-full transition duration-150 ease-in-out text-zinc-400 hover:text-zinc-500 hover:bg-zinc-100 focus:outline-none focus:bg-zinc-100 focus:text-zinc-500">
                    <svg class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                </button>
                <button x-show="mobileMenuOpen" @click="mobileMenuOpen = false" type="button" class="inline-flex justify-center items-center p-2 rounded-full transition duration-150 ease-in-out text-zinc-400 hover:text-zinc-500 hover:bg-zinc-100 focus:outline-none focus:bg-zinc-100 focus:text-zinc-500" x-cloak>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div x-data="{ open: false }" class="flex h-full md:flex-1">
                <nav class="hidden flex-1 space-x-8 h-full md:flex">
                    <a href="{{ route('wave.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none border-b-2 border-transparent @if(Request::is('dashboard')){{ 'border-b-2 border-indigo-500 text-zinc-900 focus:border-indigo-700' }}@else{{ 'text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 focus:text-zinc-700 focus:border-zinc-300' }}@endif">Dashboard</a>
                    <div x-data="{ dropdown: false }" @mouseenter="dropdown = true" @mouseleave="dropdown=false" @click.away="dropdown=false" class="inline-flex relative items-center px-1 pt-1 text-sm font-medium leading-5 border-b-2 border-transparent transition duration-150 ease-in-out cursor-pointer text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 focus:outline-none focus:text-zinc-700 focus:border-zinc-300">
                        <span>Resources</span>
                        <svg class="ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <div x-show="dropdown"
                            x-transition:enter="duration-200 ease-out scale-95"
                            x-transition:enter-start="opacity-50 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition duration-100 ease-in scale-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute top-0 left-1/2 px-2 mt-20 w-screen max-w-xs transform -translate-x-1/2 sm:px-0" x-cloak>
                            <div class="rounded-xl border shadow-md border-zinc-100">
                                <div class="overflow-hidden rounded-xl shadow-xs">
                                    <div class="grid relative z-20 gap-6 px-5 py-6 bg-white sm:p-8 sm:gap-8">
                                        <a href="{{ url('docs') }}" class="block px-5 py-3 -m-3 space-y-1 rounded-xl transition duration-150 ease-in-out hover:border-blue-500 hover:border-l-2 hover:bg-zinc-100">
                                            <p class="text-base font-medium leading-6 text-zinc-900">
                                                Documentation
                                            </p>
                                            <p class="text-xs leading-5 text-zinc-500">
                                                View The Wave Docs
                                            </p>
                                        </a>
                                        <a href="https://devdojo.com/course/wave" target="_blank" class="block px-5 py-3 -m-3 space-y-1 rounded-xl transition duration-150 ease-in-out hover:bg-zinc-100">
                                            <p class="text-base font-medium leading-6 text-zinc-900">
                                                Videos
                                            </p>
                                            <p class="text-xs leading-5 text-zinc-500">
                                                Watch videos to learn how to use Wave.
                                            </p>
                                        </a>
                                        <a href="{{ route('wave.blog') }}" class="block px-5 py-3 -m-3 space-y-1 rounded-xl transition duration-150 ease-in-out hover:bg-zinc-100">
                                            <p class="text-base font-medium leading-6 text-zinc-900">
                                                From The Blog
                                            </p>
                                            <p class="text-xs leading-5 text-zinc-500">
                                                View your application blog.
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 border-b-2 border-transparent transition duration-150 ease-in-out text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 focus:outline-none focus:text-zinc-700 focus:border-zinc-300">Support</a>
                </nav>


                <div class="flex lg:ml-6 sm:items-center">

                    @if( auth()->user()->onTrial() )
                        <div class="hidden relative justify-center items-center h-full md:flex">
                            <span class="px-3 py-1 text-xs text-red-600 bg-red-100 rounded-md border border-zinc-200">You have {{ auth()->user()->daysLeftOnTrial() }} @if(auth()->user()->daysLeftOnTrial() > 1){{ 'Days' }}@else{{ 'Day' }}@endif left on your Trial</span>
                        </div>
                    @endif

                    @include('theme::partials.notifications')

                    <!-- Profile dropdown -->
                    <div @click.away="open = false" class="flex relative items-center ml-3 h-full" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" class="flex text-sm rounded-full border-2 border-transparent transition duration-150 ease-in-out focus:outline-none focus:border-zinc-300" id="user-menu" aria-label="User menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
                                <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->avatar() . '?' . time() }}" alt="{{ auth()->user()->name }}'s Avatar">
                            </button>
                        </div>

                        <div
                            x-show="open"
                            x-transition:enter="duration-100 ease-out scale-95"
                            x-transition:enter-start="opacity-50 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition duration-50 ease-in scale-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute top-0 right-0 mt-20 w-56 rounded-xl transform origin-top-right" x-cloak>

                            <div class="bg-white rounded-xl border shadow-md border-zinc-100" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                <a href="/profile" class="block px-4 py-3 text-zinc-700 hover:text-zinc-800">

                                    <span class="block text-sm font-medium leading-tight truncate">
                                        {{ auth()->user()->name }}
                                    </span>
                                    <span class="text-xs leading-5 text-zinc-600">
                                        View Profile
                                    </span>
                                </a>
                                @impersonating
                                        <a href="{{ route('impersonate.leave') }}" class="block px-4 py-2 text-sm leading-5 text-blue-900 bg-blue-50 border-t text-zinc-700 border-zinc-100 hover:bg-blue-100 focus:outline-none focus:bg-blue-200">Leave impersonation</a>
                                @endImpersonating
                                <div class="border-t border-zinc-100"></div>
                                <div class="py-1">

                                    <div class="block px-4 py-1">
                                        <span class="inline-block px-2 my-1 -ml-1 text-xs font-medium leading-5 rounded text-zinc-600 bg-zinc-200">{{ auth()->user()->roles->first()->name }}</span>
                                    </div>
                                    @trial
                                        <a href="{{ route('wave.settings', 'plans') }}" class="block px-4 py-2 text-sm leading-5 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:bg-zinc-100 focus:text-zinc-900">Upgrade My Account</a>
                                    @endtrial
                                    @if( !auth()->guest() && auth()->user()->can('browse_admin') )
                                        <a href="{{ route('voyager.dashboard') }}" class="block px-4 py-2 text-sm leading-5 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:bg-zinc-100 focus:text-zinc-900"><i class="fa fa-bolt"></i> Admin</a>
                                    @endif
                                    <a href="" class="block px-4 py-2 text-sm leading-5 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:bg-zinc-100 focus:text-zinc-900">My Profile</a>
                                    <a href="" class="block px-4 py-2 text-sm leading-5 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:bg-zinc-100 focus:text-zinc-900">Settings</a>

                                </div>
                                <div class="border-t border-zinc-100"></div>
                                <div class="py-1">
                                    <a href="{{ route('logout') }}" class="block px-4 py-2 w-full text-sm leading-5 text-left text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:bg-zinc-100 focus:text-zinc-900" role="menuitem">
                                        Sign out
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-transition:enter="duration-300 ease-out scale-100" x-transition:enter-start="opacity-50 scale-110" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition duration-75 ease-in scale-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-100" class="absolute inset-x-0 top-0 transition transform origin-top md:hidden">
        <div class="rounded-lg shadow-lg">
            <div class="bg-white rounded-lg divide-y-0 shadow-xs divide-zinc-50">
                <div class="px-8 pt-24 pb-8">
                    <nav class="grid row-gap-8">
                        <a href="{{ route('wave.dashboard') }}" class="flex items-center p-3 -mx-2 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Dashboard
                            </div>
                        </a>
                        <a href="https://wave.devdojo.com/docs" target="_blank" class="flex items-center p-3 -mx-2 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Documentation
                            </div>
                        </a>
                        <a href="https://devdojo.com/course/wave" target="_blank" class="flex items-center p-3 -mx-2 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Videos
                            </div>
                        </a>
                        <a href="{{ route('wave.blog') }}" class="flex items-center p-3 -mx-2 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Blog
                            </div>
                        </a>
                        <a href="#" class="flex items-center p-3 -mx-2 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Support
                            </div>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    {{-- End Mobile Menu --}}
</header>
