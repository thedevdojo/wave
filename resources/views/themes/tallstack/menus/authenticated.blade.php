<div x-data="{ open: false }" class="flex h-full md:flex-1">
    <div class="flex-1 hidden h-full space-x-8 md:flex">
        <a href="{{ route('wave.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none border-b-2 border-transparent @if(Request::is('dashboard')){{ 'border-b-2 border-indigo-500 text-gray-900 focus:border-indigo-700' }}@else{{ 'text-gray-500 hover:border-gray-300 hover:text-gray-700 focus:text-gray-700 focus:border-gray-300' }}@endif">Dashboard</a>
        <div x-data="{ dropdown: false }" @mouseenter="dropdown = true" @mouseleave="dropdown=false" @click.away="dropdown=false" class="relative inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out border-b-2 border-transparent cursor-pointer hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
            <span>Resources</span>
            <svg class="w-5 h-5 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            <div x-show="dropdown"
                x-transition:enter="duration-200 ease-out scale-95"
                x-transition:enter-start="opacity-50 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition duration-100 ease-in scale-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute top-0 w-screen max-w-xs px-2 mt-20 transform -translate-x-1/2 left-1/2 sm:px-0" x-cloak>
                <div class="border border-gray-100 shadow-md rounded-xl">
                    <div class="overflow-hidden shadow-xs rounded-xl">
                        <div class="relative z-20 grid gap-6 px-5 py-6 bg-white sm:p-8 sm:gap-8">
                            <a href="{{ url('docs') }}" class="block px-5 py-3 -m-3 space-y-1 transition duration-150 ease-in-out hover:border-blue-500 hover:border-l-2 rounded-xl hover:bg-gray-100">
                                <p class="text-base font-medium leading-6 text-gray-900">
                                    Documentation
                                </p>
                                <p class="text-xs leading-5 text-gray-500">
                                    View The Wave Docs
                                </p>
                            </a>
                            <a href="https://devdojo.com/course/wave" target="_blank" class="block px-5 py-3 -m-3 space-y-1 transition duration-150 ease-in-out rounded-xl hover:bg-gray-100">
                                <p class="text-base font-medium leading-6 text-gray-900">
                                    Videos
                                </p>
                                <p class="text-xs leading-5 text-gray-500">
                                    Watch videos to learn how to use Wave.
                                </p>
                            </a>
                            <a href="{{ route('wave.blog') }}" class="block px-5 py-3 -m-3 space-y-1 transition duration-150 ease-in-out rounded-xl hover:bg-gray-100">
                                <p class="text-base font-medium leading-6 text-gray-900">
                                    From The Blog
                                </p>
                                <p class="text-xs leading-5 text-gray-500">
                                    View your application blog.
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">Support</a>
    </div>


    <div class="flex sm:ml-6 sm:items-center">

        @if( auth()->user()->onTrial() )
            <div class="relative items-center justify-center hidden h-full md:flex">
                <span class="px-3 py-1 text-xs text-red-600 bg-red-100 border border-gray-200 rounded-md">You have {{ auth()->user()->daysLeftOnTrial() }} @if(auth()->user()->daysLeftOnTrial() > 1){{ 'Days' }}@else{{ 'Day' }}@endif left on your Trial</span>
            </div>
        @endif

        @include('theme::partials.notifications')

        <!-- Profile dropdown -->
        <div @click.away="open = false" class="relative flex items-center h-full ml-3" x-data="{ open: false }">
            <div>
                <button @click="open = !open" class="flex text-sm transition duration-150 ease-in-out border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300" id="user-menu" aria-label="User menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
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
                class="absolute top-0 right-0 w-56 mt-20 origin-top-right transform rounded-xl" x-cloak>

                <div class="bg-white border border-gray-100 shadow-md rounded-xl" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                    <a href="{{ route('wave.profile', auth()->user()->username) }}" class="block px-4 py-3 text-gray-700 hover:text-gray-800">

                        <span class="block text-sm font-medium leading-tight truncate">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="text-xs leading-5 text-gray-600">
                            View Profile
                        </span>
                    </a>
                    @impersonating
                            <a href="{{ route('impersonate.leave') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 text-blue-900 border-t border-gray-100 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:bg-blue-200">Leave impersonation</a>
                    @endImpersonating
                    <div class="border-t border-gray-100"></div>
                    <div class="py-1">

                        <div class="block px-4 py-1">
                            <span class="inline-block px-2 my-1 -ml-1 text-xs font-medium leading-5 text-gray-600 bg-gray-200 rounded">{{ auth()->user()->role->display_name }}</span>
                        </div>
                        @trial
                            <a href="{{ route('wave.settings', 'plans') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Upgrade My Account</a>
                        @endtrial
                        @if( !auth()->guest() && auth()->user()->can('browse_admin') )
                            <a href="{{ route('voyager.dashboard') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><i class="fa fa-bolt"></i> Admin</a>
                        @endif
                        <a href="{{ route('wave.profile', auth()->user()->username) }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">My Profile</a>
                        <a href="{{ route('wave.settings') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Settings</a>

                    </div>
                    <div class="border-t border-gray-100"></div>
                    <div class="py-1">
                        <a href="{{ route('logout') }}" class="block w-full px-4 py-2 text-sm leading-5 text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                            Sign out
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
