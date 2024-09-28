{{-- <div x-show="mobileMenuOpen" x-transition:enter="duration-300 ease-out scale-100" x-transition:enter-start="opacity-50 scale-110" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition duration-75 ease-in scale-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-100" class="absolute inset-x-0 top-0 z-40 transition transform origin-top md:hidden">
    <div class="shadow-lg">
        <div class="bg-white divide-y-2 divide-zinc-50 shadow-xs">
            <div class="pt-6 pb-6 space-y-6">
                <div class="flex justify-between items-center px-8 mt-1">
                    <div>
                        <x-logo class="w-auto h-7"></x-logo>
                    </div>
                    <div class="-mr-2">
                        <button @click="mobileMenuOpen = false" type="button" class="inline-flex justify-center items-center p-2 rounded-full transition duration-150 ease-in-out text-zinc-400 hover:text-zinc-500 hover:bg-zinc-100 focus:outline-none focus:bg-zinc-100 focus:text-zinc-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <nav class="grid row-gap-8">
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 ml-0.5 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Authentication
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 ml-0.5 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Roles and Permissions
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 ml-0.5 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Subscriptions
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 ml-0.5 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Posts and Pages
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 ml-0.5 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Themes
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 ml-0.5 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Settings and More
                            </div>
                        </a>
                    </nav>
                </div>
            </div>
            <div class="px-8 py-6 space-y-6">
                <div class="grid grid-cols-2 px-1 pb-4 row-gap-4 col-gap-8">
                    <a href="/#pricing" class="text-base font-medium leading-6 transition duration-150 ease-in-out text-zinc-900 hover:text-zinc-700">
                        Pricing
                    </a>
                    <a href="#" class="text-base font-medium leading-6 transition duration-150 ease-in-out text-zinc-900 hover:text-zinc-700">
                        Docs
                    </a>
                    <a href="#" class="text-base font-medium leading-6 transition duration-150 ease-in-out text-zinc-900 hover:text-zinc-700">
                        Blog
                    </a>
                    <a href="#" class="text-base font-medium leading-6 transition duration-150 ease-in-out text-zinc-900 hover:text-zinc-700">
                        Videos
                    </a>
                </div>
                <div class="space-y-6">
                    <span class="flex w-full rounded-md shadow-sm">
                        <a href="{{ route('register') }}" class="flex justify-center items-center px-4 py-3 w-full text-base font-medium leading-6 text-white bg-blue-600 rounded-full border border-transparent transition duration-150 ease-in-out hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave active:bg-blue-700">
                            Sign up
                        </a>
                    </span>
                    <p class="text-base font-medium leading-6 text-center text-zinc-500">
                        Existing customer?
                        <a href="{{ route('login') }}" class="text-blue-600 transition duration-150 ease-in-out hover:text-blue-500">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> --}}
