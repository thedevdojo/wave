<div x-data="{ open: false }" class="flex h-full md:flex-1">
    <div class="hidden flex-1 space-x-8 h-full font-semibold md:flex">
        <a href="{{ route('wave.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm leading-5 transition duration-150 ease-in-out focus:outline-none border-b-2 border-transparent @if(Request::is('dashboard')){{ 'text-zinc-900' }}@else{{ 'text-zinc-800 hover:text-zinc-900' }}@endif">Dashboard</a>
        <div x-data="{ dropdown: false }" @mouseenter="dropdown = true" @mouseleave="dropdown=false" @click.away="dropdown=false" class="inline-flex relative items-center px-1 pt-1 text-sm leading-5 border-b-2 border-transparent transition duration-150 ease-in-out cursor-pointer text-zinc-800 hover:text-zinc-900 hover:border-zinc-300">
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
                            <a href="{{ route('blog') }}" class="block px-5 py-3 -m-3 space-y-1 rounded-xl transition duration-150 ease-in-out hover:bg-zinc-100">
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
        <a href="#" class="inline-flex items-center px-1 pt-1 text-sm leading-5 border-b-2 border-transparent transition duration-150 ease-in-out hover:text-zinc-900">Support</a>
    </div>

    <div class="flex sm:ml-6 sm:items-center">

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
                    <a href="{{ route('wave.profile', auth()->user()->username) }}" class="block px-4 py-3 text-zinc-700 hover:text-zinc-800">

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
                        @if( !auth()->guest() && auth()->user()->isAdmin() )
                            <a href="/admin" class="block px-4 py-2 text-sm leading-5 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:bg-zinc-100 focus:text-zinc-900"><i class="fa fa-bolt"></i> Admin</a>
                        @endif
                        <a href="{{ route('wave.profile', auth()->user()->username) }}" class="block px-4 py-2 text-sm leading-5 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:bg-zinc-100 focus:text-zinc-900">My Profile</a>
                        <a href="{{ route('wave.settings') }}" class="block px-4 py-2 text-sm leading-5 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:bg-zinc-100 focus:text-zinc-900">Settings</a>

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
