<header x-data="{ mobileMenuOpen: false, scrolled: false, topOffset: 20 }"
        x-init="
            window.addEventListener('scroll', function() {
                if(window.pageYOffset > topOffset){
                    scrolled = true;
                } else {
                    scrolled = false;
                }
            })
        " :class="{ 'border-zinc-100 bg-white/90 backdrop-blur-xl' : scrolled, 'border-transparent bg-white' : !scrolled }" class="box-content fixed z-30 w-full h-24 border-b">
    <x-container>
        <div class="flex relative z-30 justify-between items-center h-24 md:space-x-8">
            <div class="inline-flex relative z-20">
            <!-- data-replace='{ "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }' -->
                <a href="{{ route('home') }}" class="flex justify-center items-center space-x-3 text-blue-500 brightness-0 transition-all duration-300 ease-out transform hover:brightness-100 grayscale-100">
                   <x-logo class="w-auto h-5"></x-logo>
                </a>
            </div>
            <div class="flex flex-grow justify-end -my-2 -mr-2 md:hidden">
                <button @click="mobileMenuOpen = true" type="button" class="inline-flex justify-center items-center p-2 rounded-full transition duration-150 ease-in-out text-zinc-400 hover:text-zinc-500 hover:bg-zinc-100 focus:outline-none focus:bg-zinc-100 focus:text-zinc-500">
                    <svg class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                </button>
            </div>

            <nav class="relative h-full">
                <ul id="menu" class="flex hidden flex-1 gap-x-8 justify-center items-center ml-0 w-full h-full border-t border-gray-100 md:flex md:w-auto md:items-center md:border-t-0 md:flex-row">
                    <li class="flex relative z-30 flex-col items-start h-full border-b border-gray-100 md:border-b-0 group md:flex-row md:items-center">
                        <a href="#_" class="flex gap-1 items-center px-6 w-full h-16 text-sm font-semibold text-gray-700 transition duration-300 md:h-full md:px-0 md:w-auto hover:text-gray-900">
                            <span class="">Platform</span>
                            <svg class="w-5 h-5 transition-all duration-300 ease-out group-hover:-rotate-180" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" class=""></path></svg>
                        </a>
                        <div class="hidden top-0 left-0 invisible space-y-3 w-full w-screen bg-white border-t border-b border-gray-100 shadow-md opacity-0 transition-all duration-300 ease-out -translate-y-2 md:block group-hover:block group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 md:fixed md:mt-24">
                            <ul class="flex flex-col justify-between px-8 mx-auto max-w-6xl md:px-12 md:flex-row">
                                <li class="w-full border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block p-6 h-full text-lg font-semibold transition duration-300 hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <svg class="mb-5 w-auto h-6" viewBox="0 0 73 49" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M46.868 24c0 12.426-10.074 22.5-22.5 22.5-12.427 0-22.5-10.074-22.5-22.5S11.94 1.5 24.368 1.5c12.426 0 22.5 10.074 22.5 22.5Z" fill="#68DBFF"></path><path d="M71.132 24c0 12.426-9.975 22.5-22.28 22.5-12.304 0-22.278-10.074-22.278-22.5S36.547 1.5 48.852 1.5c12.304 0 22.28 10.074 22.28 22.5Z" fill="#FF7917"></path><path d="M36.67 42.842C42.81 38.824 46.868 31.886 46.868 24c0-7.886-4.057-14.824-10.198-18.841A22.537 22.537 0 0 0 26.573 24 22.537 22.537 0 0 0 36.67 42.842Z" fill="#5D2C02"></path></svg>
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature One</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Highlight your main feature here</span>
                                    </a>
                                </li>
                                <li class="w-full border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block p-6 h-full text-lg font-semibold transition duration-300 hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <svg class="mt-0.5 mb-5 w-auto h-5" viewBox="0 0 78 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M55.5 0h22l-19 32h-22l19-32Z" fill="#FF7A00"></path><path d="M35.5 0h16l-19 32h-16l19-32Z" fill="#FF9736"></path><path d="M19.5 0h12l-19 32H.5l19-32Z" fill="#FFBC7D"></path></svg>
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature Two</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Brief description of another feature</span>
                                    </a>
                                </li>
                                <li class="w-full border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block p-6 h-full text-lg font-semibold transition duration-300 hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <svg class="mb-4 w-auto h-7" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.684 40.138c11.046 0 20-8.954 20-20s-8.954-20-20-20-20 8.954-20 20 8.954 20 20 20Zm6.24-30.683c.303-1.079-.744-1.717-1.7-1.036l-13.347 9.509c-1.037.738-.874 2.21.245 2.21h3.515v-.027h6.85l-5.582 1.97-2.46 8.74c-.304 1.079.743 1.717 1.7 1.036l13.346-9.508c1.037-.74.874-2.211-.245-2.211h-5.33l3.007-10.683Z" fill="#F15757"></path></svg>
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature Three</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Describe another one of your features here</span>
                                    </a>
                                </li>
                                <li class="w-full border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block p-6 h-full text-lg font-semibold transition duration-300 hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <svg class="mb-4 w-auto h-7" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.101 40.323c11.046 0 20-8.954 20-20 0-11.045-8.954-20-20-20-11.045 0-20 8.955-20 20 0 11.046 8.955 20 20 20Zm3.087-24.547a4.366 4.366 0 1 0-6.173 0l3.086 3.087 3.087-3.087Zm1.46 7.634a4.366 4.366 0 1 0 0-6.174l-3.086 3.087 3.087 3.087Zm-1.46 7.635a4.366 4.366 0 0 0 0-6.174l-3.087-3.087-3.086 3.087a4.366 4.366 0 0 0 6.173 6.174ZM9.38 23.41a4.366 4.366 0 0 1 6.174-6.174l3.087 3.087-3.087 3.087a4.366 4.366 0 0 1-6.174 0Z" fill="#7F57F1"></path></svg>
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature Four</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Add a fourth feature or even a resource here</span>
                                    </a>
                                </li>
                                <li class="w-full border-r border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block p-6 h-full text-lg font-semibold transition duration-300 hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <svg class="mb-4 w-auto h-7" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.654 3.891c-1.43 1-2.724 2.184-3.847 3.515 4.59-.426 10.42.27 17.189 3.654 7.228 3.614 13.05 3.737 17.1 2.955a19.888 19.888 0 0 0-1.378-3.199c-4.638.49-10.583-.158-17.511-3.622-4.4-2.2-8.278-3.106-11.553-3.303Zm26.355 3.07A19.95 19.95 0 0 0 20.1.293c-1.74 0-3.427.222-5.036.64 2.18.594 4.494 1.464 6.93 2.682 5.073 2.536 9.453 3.353 13.014 3.344Zm4.953 10.96c-4.894.967-11.652.769-19.755-3.283-7.576-3.788-13.605-3.74-17.672-2.836-.21.046-.415.095-.615.146a19.85 19.85 0 0 0-1.262 3.64c.326-.087.662-.17 1.01-.247 4.933-1.096 11.904-1.048 20.328 3.164 7.576 3.788 13.605 3.74 17.672 2.836.139-.03.276-.063.411-.096a20.296 20.296 0 0 0-.117-3.323Zm-.536 7.545c-4.846.847-11.408.522-19.219-3.384-7.576-3.787-13.605-3.74-17.672-2.836-.902.2-1.714.445-2.43.703-.003.114-.004.23-.004.345 0 11.045 8.955 20 20 20 9.258 0 17.046-6.29 19.325-14.828Z" fill="#1E5DFF"></path></svg>
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature Five</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Add another feature highlight or link to a page</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="flex relative z-30 flex-col items-start h-full border-b border-gray-100 md:border-b-0 group md:flex-row md:items-center">
                        <a href="#_" class="flex gap-1 items-center px-6 w-full h-16 text-sm font-semibold text-gray-700 transition duration-300 md:h-full md:px-0 md:w-auto hover:text-gray-900">
                            <span class="">Resources</span>
                            <svg class="w-5 h-5 transition-all duration-300 ease-out group-hover:-rotate-180" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" class=""></path></svg>
                        </a>
                        <div class="hidden top-0 left-0 invisible space-y-3 w-full w-screen bg-white border-t border-b border-gray-100 shadow-sm opacity-0 transition-all duration-300 ease-out -translate-y-2 md:block group-hover:block group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 md:fixed md:mt-24">
                            <ul class="flex flex-col justify-between px-8 mx-auto max-w-6xl md:flex-row md:px-12">
                                <div class="flex flex-row gap-5 p-5 w-full border-r border-l border-zinc-100">
                                    <div class="gap-5 space-y-2 w-auto">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100 group">
                                            <span class="block mb-1 font-medium text-black">Authentication</span>
                                            <span class="block font-light leading-5 opacity-50">Configure the login, register, and forgot password for your app</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Roles and Permissions</span>
                                            <span class="block leading-5 opacity-50">We utilize the bullet-proof Spatie Permissions package</span>
                                        </a>
                                    </div>
                                    <div class="gap-5 space-y-2 w-auto">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Posts and Pages</span>
                                            <span class="block font-light leading-5 opacity-50">Easily write blog articles and create pages for your application</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Themes</span>
                                            <span class="block leading-5 opacity-50">Kick-start your app with a pre-built theme or create your own</span>
                                        </a>
                                    </div>
                                    <div class="gap-5 space-y-2 w-auto">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Settings and More</span>
                                            <span class="block leading-5 opacity-50">Easily create and update app settings. And so much more</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Subscriptions</span>
                                            <span class="block leading-5 opacity-50">Integration payments and let users subscribe to a plan</span>
                                        </a>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </li>
                    <li class="px-6 h-16 border-b border-gray-100 md:px-0 md:border-b-0 md:h-full">
                        <a href="{{ route('pricing') }}" class="flex items-center h-full text-sm font-semibold text-gray-700 transition duration-300 hover:text-gray-900">
                            Pricing
                        </a>
                    </li>
                    <li class="px-6 h-16 border-b border-gray-100 md:px-0 md:border-b-0 md:h-full">
                        <a href="#_" class="flex items-center h-full text-sm font-semibold text-gray-700 transition duration-300 hover:text-gray-900">About</a>
                    </li>

                    <a href="#_" class="block px-5 py-3 text-base font-medium text-center text-white bg-blue-600 md:hidden">View Dashboard</a>
                </ul>
            </nav>
            
            @guest
                <div class="hidden relative z-30 flex-shrink-0 justify-center items-center space-x-3 h-full text-sm md:flex">
                    <x-button href="{{ route('login') }}" tag="a" class="text-sm" color="secondary">Login</x-button>
                    <x-button href="{{ route('register') }}" tag="a" class="text-sm">Sign Up</x-button>
                </div>
            @else
                <x-button href="{{ route('login') }}" tag="a" class="text-sm" class="relative z-20 flex-shrink-0">View Dashboard</x-button>
            @endguest

        </div>
    </x-container>

    @include('theme::partials.menus.marketing-mobile')
</header>
