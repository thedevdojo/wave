<nav class="flex items-center justify-end flex-1 hidden w-full h-full space-x-10 md:flex">
    <div @mouseenter="dropdown = true" @mouseleave="dropdown=false" @click.away="dropdown=false" x-data="{ dropdown: false }" class="relative h-full select-none">
        <div :class="{ 'text-wave-600': dropdown, 'text-gray-500': !dropdown }" class="inline-flex items-center h-full space-x-2 text-base font-medium leading-6 text-gray-500 transition duration-150 ease-in-out cursor-pointer select-none group hover:text-wave-600 focus:outline-none focus:text-wave-600">
            <span>Product</span>
            <svg class="w-5 h-5 text-gray-400 transition duration-150 ease-in-out group-hover:text-wave-600 group-focus:text-wave-600" x-bind:class="{ 'text-wave-600': dropdown, 'text-gray-400': !dropdown }" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </div>

        <div x-show="dropdown"
            x-transition:enter="duration-200 ease-out scale-95"
            x-transition:enter-start="opacity-50 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition duration-100 ease-in scale-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed w-full transform -translate-x-1/2 xl:w-screen xl:max-w-6xl xl:px-2 xl:absolute sm:px-0 lg:ml-0 left-1/2" x-cloak>

            <div class="overflow-hidden shadow-lg xl:rounded-xl">
                <div class="flex items-stretch justify-center overflow-hidden bg-white shadow-xs xl:rounded-xl">

                    <div class="flex flex-col">
                        <div class="flex h-full">
                            <div class="relative flex flex-col items-start justify-start hidden w-full h-full max-w-xs bg-center bg-cover lg:block" style="background-image:url('https://cdn.devdojo.com/images/october2020/wave-menu-bg.jpeg')">
                                <div class="relative flex flex-col items-start justify-center w-full h-full px-16 py-8">

                                    <img src="https://cdn.devdojo.com/images/april2021/wave-light-logo.png" class="z-20 w-auto h-10">
                                    <h3 class="z-30 mt-1 mt-3 text-lg font-thin text-wave-200">The Software as a Service Starter Kit.</h3>

                                    <span class="relative z-20 inline-flex mt-5 rounded-md shadow-sm">
                                        <a href="https://devdojo.com/wave" class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border border-transparent rounded-md text-wave-500 hover:bg-gray-100 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray active:bg-gray-100">
                                            Get Wave Today
                                            <span class="absolute top-0 right-0 px-3 py-1 -mt-4 -mr-8 text-xs text-white rounded-full bg-wave-400">Version 2</span>
                                        </a>
                                    </span>
                                </div>
                                <div class="absolute inset-0 w-full h-full opacity-75 bg-wave-600"></div>
                                <div class="absolute inset-0 w-full h-full opacity-75 bg-gradient-to-br from-wave-600 to-indigo-600"></div>
                            </div>
                            <div class="relative z-20 grid gap-6 px-5 pt-6 pb-8 bg-white border-t border-b border-transparent xl:border-gray-200 sm:gap-8 sm:p-8">
                                <a href="#" class="flex items-start inline-block p-3 -m-3 space-x-4 transition duration-150 ease-in-out rounded-lg group">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mt-1 mr-3 transition duration-300 ease-in-out transform scale-100 bg-indigo-100 rounded-full text-wave-600 group-hover:text-white group-hover:bg-wave-600 group-hover:scale-110 group-hover:rotate-3 -rotate-3">
                                        <svg class="relative flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-base font-medium leading-6 text-gray-900">
                                            Authentication
                                        </p>
                                        <p class="text-sm leading-5 text-gray-500">
                                            Full authentication system including register, login, and lost password.
                                        </p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start inline-block p-3 -m-3 space-x-4 transition duration-150 ease-in-out rounded-lg group">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mt-1 mr-3 transition duration-300 ease-in-out transform scale-100 bg-indigo-100 rounded-full text-wave-600 group-hover:text-white group-hover:bg-wave-600 group-hover:scale-110 group-hover:rotate-3 -rotate-3">
                                        <svg class="relative flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-base font-medium leading-6 text-gray-900">
                                            Billing
                                        </p>
                                        <p class="text-sm leading-5 text-gray-500">
                                            Subscription billing built on top of Paddle
                                        </p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start inline-block p-3 -m-3 space-x-4 transition duration-150 ease-in-out rounded-lg group">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mt-1 mr-3 transition duration-300 ease-in-out transform scale-100 bg-indigo-100 rounded-full text-wave-600 group-hover:text-white group-hover:bg-wave-600 group-hover:scale-110 group-hover:rotate-3 -rotate-3">
                                        <svg class="relative flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-base font-medium leading-6 text-gray-900">
                                            User Profiles
                                        </p>
                                        <p class="text-sm leading-5 text-gray-500">
                                            User profiles with image uploading and profile settings functionality.
                                        </p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start inline-block p-3 -m-3 space-x-4 transition duration-150 ease-in-out rounded-lg group">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mt-1 mr-3 transition duration-300 ease-in-out transform scale-100 bg-indigo-100 rounded-full text-wave-600 group-hover:text-white group-hover:bg-wave-600 group-hover:scale-110 group-hover:rotate-3 -rotate-3">
                                        <svg class="relative flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-base font-medium leading-6 text-gray-900">
                                            User Roles
                                        </p>
                                        <p class="text-sm leading-5 text-gray-500">
                                            User roles and permissions based on user plans.
                                        </p>
                                    </div>
                                </a>



                            </div>

                            <div class="relative z-20 grid gap-6 px-5 pt-6 pb-8 bg-white border-t border-b border-r border-transparent rounded-r-xl xl:border-gray-200 sm:gap-8 sm:p-8">
                            <a href="#" class="flex items-start inline-block p-3 -m-3 space-x-4 transition duration-150 ease-in-out rounded-lg group">
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mt-1 mr-3 transition duration-300 ease-in-out transform scale-100 bg-indigo-100 rounded-full text-wave-600 group-hover:text-white group-hover:bg-wave-600 group-hover:scale-110 group-hover:rotate-3 -rotate-3">
                                    <svg class="relative flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-base font-medium leading-6 text-gray-900">
                                        Notifications
                                    </p>
                                    <p class="text-sm leading-5 text-gray-500">
                                        User notification system that's easy to implement in your SAAS.
                                    </p>
                                </div>
                            </a>
                            <a href="#" class="flex items-start inline-block p-3 -m-3 space-x-4 transition duration-150 ease-in-out rounded-lg group">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mt-1 mr-3 transition duration-300 ease-in-out transform scale-100 bg-indigo-100 rounded-full text-wave-600 group-hover:text-white group-hover:bg-wave-600 group-hover:scale-110 group-hover:rotate-3 -rotate-3">
                                        <svg class="relative flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-base font-medium leading-6 text-gray-900">
                                            Fully Functional Blog
                                        </p>
                                        <p class="text-sm leading-5 text-gray-500">
                                            Add blog posts to your application to drive-in organic traffic.
                                        </p>
                                    </div>
                                </a>

                            <a href="#" class="flex items-start inline-block p-3 -m-3 space-x-4 transition duration-150 ease-in-out rounded-lg group">
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mt-1 mr-3 transition duration-300 ease-in-out transform scale-100 bg-indigo-100 rounded-full text-wave-600 group-hover:text-white group-hover:bg-wave-600 group-hover:scale-110 group-hover:rotate-3 -rotate-3">
                                    <svg class="relative flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-base font-medium leading-6 text-gray-900">
                                        Themes
                                    </p>
                                    <p class="text-sm leading-5 text-gray-500">
                                        Theme support with your favorite CSS libraries.
                                    </p>
                                </div>
                            </a>
                            <a href="#" class="flex items-start inline-block p-3 -m-3 space-x-4 transition duration-150 ease-in-out rounded-lg group">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mt-1 mr-3 transition duration-300 ease-in-out transform scale-100 bg-indigo-100 rounded-full text-wave-600 group-hover:text-white group-hover:bg-wave-600 group-hover:scale-110 group-hover:rotate-3 -rotate-3">
                                        <svg class="relative flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-base font-medium leading-6 text-gray-900">
                                            Developer API
                                        </p>
                                        <p class="text-sm leading-5 text-gray-500">
                                            Grant access keys and tokens for your users.
                                        </p>
                                    </div>
                                </a>
                        </div>

                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>

    <a href="/#pricing" class="text-base font-medium leading-6 text-gray-500 transition duration-150 ease-in-out hover:text-wave-600 focus:outline-none focus:text-wave-600">
        Pricing
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
                                    A series of videos to help you get started.
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
                                    Wave comes with a full blogging platform
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
        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-500 hover:bg-wave-600 focus:outline-none focus:border-indigo-700 focus:shadow-outline-wave active:bg-wave-700">
            Sign up
        </a>
    </span>
</nav>
