<nav class="flex items-center justify-end flex-1 hidden w-full h-full space-x-10 md:flex">
    <div @mouseenter="dropdown = true" @mouseleave="dropdown=false" @click.away="dropdown=false" x-data="{ dropdown: false }" class="relative h-full select-none">
        <div :class="{ 'text-wave-600': dropdown, 'text-gray-500': !dropdown }" class="inline-flex items-center h-full space-x-2 text-base font-medium leading-6 text-gray-500 transition duration-150 ease-in-out cursor-pointer select-none group hover:text-wave-600 focus:outline-none focus:text-wave-600">
            <span>Products</span>
            <svg class="w-5 h-5 text-gray-400 transition duration-150 ease-in-out group-hover:text-wave-600 group-focus:text-wave-600" x-bind:class="{ 'text-wave-600': dropdown, 'text-gray-400': !dropdown }" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </div>
    <a href="/#pricing" class="text-base font-medium leading-6 text-gray-500 transition duration-150 ease-in-out hover:text-wave-600 focus:outline-none focus:text-wave-600">
        About
    </a>
    <a href="{{ route('wave.blog') }}" class="text-base font-medium leading-6 text-gray-500 transition duration-150 ease-in-out hover:text-wave-600 focus:outline-none focus:text-wave-600">
        Blog
    </a>

    <div @mouseenter="dropdown = true" @mouseleave="dropdown=false" @click.away="dropdown=false" x-data="{ dropdown: false }" class="relative h-full select-none">
        <div @click="dropdown = !dropdown" :class="{ 'text-wave-600': dropdown, 'text-gray-500': !dropdown }" class="inline-flex items-center h-full space-x-2 text-base font-medium leading-6 text-gray-500 transition duration-150 ease-in-out cursor-pointer select-none hover:text-wave-600 focus:outline-none focus:text-wave-500">
            <span>More</span>
            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </div>

        <div
            x-show="dropdown"
            x-transition:enter="duration-200 ease-out scale-95"
            x-transition:enter-start="opacity-50 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition duration-100 ease-in scale-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute w-screen max-w-sm px-2 transform -translate-x-1/2 left-1/2 sm:px-0"
            x-cloak>
            <div class="shadow-lg rounded-xl">
                <div class="overflow-hidden border border-gray-100 shadow-md rounded-xl">
                    <div class="relative z-20 grid bg-white">
                        <a href="/docs" class="flex items-start p-10 -m-3 space-x-3 transition duration-150 ease-in-out rounded-lg pb-7 group">
                            <svg class="flex-shrink-0 w-4 h-4 mt-1 text-gray-500 duration-200 group-hover:text-gray-800 transition-color ease" fill="currentColor" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M4.04 2c1.91 0 3.46.9 3.46 2.55V15c-.45 0-.9-.3-1.17-.53-.86-.71-2.52-.75-4.31-.75H.83a.82.82 0 0 1-.59-.25.86.86 0 0 1-.24-.6V2.85C0 2.38.37 2 .83 2h3.2zm11.13 0c.22 0 .43.1.59.25.15.16.24.38.24.6v10.02c0 .23-.09.44-.24.6a.82.82 0 0 1-.6.25h-1.18c-1.8 0-3.45.04-4.3.75-.28.23-.73.53-1.18.53V4.55C8.5 2.91 10.05 2 11.96 2h3.2z" fill-rule="evenodd"></path></svg>
                            <div class="space-y-1">
                                <p class="text-base font-medium leading-6 text-gray-700 duration-200 group-hover:text-gray-900 transition-color ease">
                                    Documentation
                                </p>
                                <p class="text-xs font-normal leading-5 text-gray-500 duration-200 group-hover:text-gray-700 transition-color ease">
                                    Learn how to setup, install, and configure wave.
                                </p>
                            </div>
                        </a>
                        <a href="https://devdojo.com/course/wave" target="_blank" class="flex items-start px-10 -m-3 space-x-3 transition duration-150 ease-in-out rounded-lg py-7 group">
                            <svg class="flex-shrink-0 w-6 h-6 text-gray-500 duration-200 group-hover:text-gray-800 transition-color ease" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                            <div class="space-y-1">
                                <p class="text-base font-medium leading-6 text-gray-700 duration-200 group-hover:text-gray-900 transition-color ease">
                                    Videos
                                </p>
                                <p class="text-xs font-normal leading-5 text-gray-500 duration-200 group-hover:text-gray-700 transition-color ease">
                                    Videos for motivation!
                                </p>
                            </div>
                        </a>
                        <a href="{{ route('wave.blog') }}" class="flex items-start px-10 pb-10 -m-3 space-x-3 transition duration-150 ease-in-out rounded-lg pt-7 group">
                            <svg class="flex-shrink-0 w-6 h-6 text-gray-500 duration-200 group-hover:text-gray-800 transition-color ease" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path><path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path></svg>
                            <div class="space-y-1">
                                <p class="text-base font-medium leading-6 text-gray-700 duration-200 group-hover:text-gray-900 transition-color ease">
                                    Blog
                                </p>
                                <p class="text-xs font-normal leading-5 text-gray-500 duration-200 group-hover:text-gray-700 transition-color ease">
                                    Don't forget to join our growing community!
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-1 h-5 mx-10 border-r border-gray-300"></div>
    <a href="{{ route('login') }}" class="text-base font-medium leading-6 text-gray-500 whitespace-no-wrap hover:text-wave-600 focus:outline-none focus:text-gray-900">
        Sign in
    </a>
    <span class="inline-flex rounded-md shadow-sm">
        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-500 hover: focus:outline-none focus:border-indigo-700 focus:shadow-outline-wave active:bg-wave-700">
            Sign up
        </a>
    </span>
</nav>
