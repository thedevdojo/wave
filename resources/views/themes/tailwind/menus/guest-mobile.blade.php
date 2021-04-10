<div x-show="mobileMenuOpen" x-transition:enter="duration-300 ease-out scale-100" x-transition:enter-start="opacity-50 scale-110" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition duration-75 ease-in scale-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-100" class="absolute inset-x-0 top-0 transition origin-top transform md:hidden">
    <div class="shadow-lg">
        <div class="bg-white divide-y-2 shadow-xs divide-gray-50">
            <div class="pt-6 pb-6 space-y-6">
                <div class="flex items-center justify-between px-8 mt-1">
                    <div>
                        <svg class="w-9 h-9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 205 205"><defs><linearGradient id="a" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stop-color="#1B56E7"/><stop offset="100%" stop-color="#3987E9"/></linearGradient><linearGradient id="b" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stop-color="#07A2FF"/><stop offset="100%" stop-color="#3E78EF"/></linearGradient><linearGradient id="c" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stop-color="#4ADBFA"/><stop offset="100%" stop-color="#24B4F2"/></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="url(#a)" d="M182.63 37c14.521 18.317 22.413 41.087 22.37 64.545C205 158.68 159.1 205 102.486 205c-39.382-.01-75.277-22.79-92.35-58.605C-6.939 110.58-2.172 68.061 22.398 37a105.958 105.958 0 00-9.15 43.352c0 54.239 39.966 98.206 89.265 98.206 49.3 0 89.265-43.973 89.265-98.206A105.958 105.958 0 00182.629 37z"/><path fill="url(#b)" d="M103.11 0A84.144 84.144 0 01150 14.21C117.312-.651 78.806 8.94 56.7 37.45c-22.105 28.51-22.105 68.58 0 97.09 22.106 28.51 60.612 38.101 93.3 23.239-30.384 20.26-70.158 18.753-98.954-3.75-28.797-22.504-40.24-61.021-28.47-95.829C34.346 23.392 66.723.002 103.127.006L103.11 0z"/><path fill="url(#c)" d="M116.479 13c36.655-.004 67.014 28.98 69.375 66.234 2.36 37.253-24.089 69.971-60.44 74.766 29.817-8.654 48.753-38.434 44.308-69.685-4.445-31.25-30.9-54.333-61.904-54.014-31.003.32-56.995 23.944-60.818 55.28v-1.777C46.99 44.714 78.096 13.016 116.479 13z"/></g></svg>
                    </div>
                    <div class="-mr-2">
                        <button @click="mobileMenuOpen = false" type="button" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <nav class="grid row-gap-8">
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                            <svg class="flex-shrink-0 w-6 h-6 ml-0.5 text-wave-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            <div class="text-base font-medium leading-6 text-gray-900">
                                Authentication
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                            <svg class="flex-shrink-0 w-6 h-6 ml-0.5 text-wave-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <div class="text-base font-medium leading-6 text-gray-900">
                                Billing
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                            <svg class="flex-shrink-0 w-6 h-6 ml-0.5 text-wave-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="text-base font-medium leading-6 text-gray-900">
                                User Profiles
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                            <svg class="flex-shrink-0 w-6 h-6 ml-0.5 text-wave-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                            <div class="text-base font-medium leading-6 text-gray-900">
                                Themes
                            </div>
                        </a>
                        <a href="#" class="flex items-center px-8 py-3 space-x-3 transition duration-150 ease-in-out rounded-md hover:bg-gray-50">
                            <svg class="flex-shrink-0 w-6 h-6 ml-0.5 text-wave-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div class="text-base font-medium leading-6 text-gray-900">
                                Developer API
                            </div>
                        </a>
                    </nav>
                </div>
            </div>
            <div class="px-8 py-6 space-y-6">
                <div class="grid grid-cols-2 row-gap-4 col-gap-8 px-1 pb-4">
                    <a href="/#pricing" class="text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out hover:text-gray-700">
                        Pricing
                    </a>
                    <a href="#" class="text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out hover:text-gray-700">
                        Docs
                    </a>
                    <a href="#" class="text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out hover:text-gray-700">
                        Blog
                    </a>
                    <a href="#" class="text-base font-medium leading-6 text-gray-900 transition duration-150 ease-in-out hover:text-gray-700">
                        Videos
                    </a>
                </div>
                <div class="space-y-6">
                    <span class="flex w-full rounded-md shadow-sm">
                        <a href="{{ route('register') }}" class="flex items-center justify-center w-full px-4 py-3 text-base font-medium leading-6 text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave active:bg-wave-700">
                            Sign up
                        </a>
                    </span>
                    <p class="text-base font-medium leading-6 text-center text-gray-500">
                        Existing customer?
                        <a href="{{ route('login') }}" class="transition duration-150 ease-in-out text-wave-600 hover:text-wave-500">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
