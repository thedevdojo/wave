<header 
    x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false, 
        showOverlay: false, 
        topOffset: '5',
        evaluateScrollPosition(){
            if(window.pageYOffset > this.topOffset){
                this.scrolled = true;
            } else {
                this.scrolled = false;
            }
        } 
    }"
    x-init="
        evaluateScrollPosition();
        window.addEventListener('scroll', function() {
            evaluateScrollPosition(); 
        })
    " 
    :class="{ 'border-gray-200/60 bg-white/90 backdrop-blur-lg' : scrolled, 'border-transparent bg-transparent translate-y-0' : !scrolled }" 
    class="box-content sticky top-0 z-30 w-full h-24 border-b" 
    x-cloak
>
    <div 
        x-show="showOverlay"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="absolute inset-0 w-full h-screen pt-24">
        <div class="w-screen h-full bg-black/50"></div>
    </div>
    <x-container>
        <div class="z-30 flex items-center justify-between h-24 md:space-x-8">
            <div class="relative z-20 inline-flex">
                <a href="{{ route('home') }}" class="flex items-center justify-center space-x-3 font-bold text-zinc-900">
                   <x-logo class="w-auto h-8"></x-logo>
                </a>
            </div>
            <div class="flex justify-end flex-grow md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 transition duration-150 ease-in-out rounded-full text-zinc-400 hover:text-zinc-500 hover:bg-zinc-100">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <nav :class="{ 'hidden md:flex' : !mobileMenuOpen, 'block md:flex md:relative absolute top-0 left-0 md:w-auto w-screen md:h-auto h-screen md:pt-0 pt-24 pointer-events-none md:z-10 z-50' : mobileMenuOpen }" class="h-full">
                <ul :class="{ 'hidden md:flex' : !mobileMenuOpen, 'flex flex-col absolute md:relative md:w-auto w-screen h-full md:h-full md:overflow-auto overflow-scroll md:pb-0 pb-24 bg-white md:bg-transparent' : mobileMenuOpen }" id="menu" class="items-stretch pointer-events-auto md:items-center justify-start md:justify-center flex-1 w-full h-full ml-0 border-t border-gray-100 gap-x-8 md:w-auto md:items-center md:border-t-0 md:flex-row">
                    <li @mouseenter="showOverlay=true" @mouseleave="showOverlay=false" class="z-30 flex flex-col items-start h-auto md:h-full border-b border-gray-100 md:border-b-0 group md:flex-row md:items-center">
                        <a href="#_" class="flex items-center w-full hover:bg-gray-100 md:hover:bg-transparent h-16 gap-1 px-6 text-sm font-semibold text-gray-700 transition duration-300 md:h-full md:px-0 md:w-auto hover:text-gray-900">
                            <span class="">Platform</span>
                            <svg class="w-5 h-5 transition-all duration-300 ease-out group-hover:-rotate-180" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" class=""></path></svg>
                        </a>
                        <div class="top-0 left-0 invisible hidden w-screen space-y-3 transition-transform duration-300 ease-out md:-translate-y-2 bg-white border-t border-b border-gray-100 shadow-md opacity-0 md:mt-24 md:block group-hover:block group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 md:absolute">
                            <ul class="flex flex-col justify-between max-w-6xl mx-auto md:px-12 md:flex-row">
                                <li class="w-full border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="demoButtonClickMessage(event)" class="block h-full p-6 text-lg font-semibold hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/anchor.png" class="w-12 h-auto" alt="feature 1 icon" />
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature One</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Highlight your main feature here</span>
                                    </a>
                                </li>
                                <li class="w-full border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block h-full p-6 text-lg font-semibold hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/turtle.png" class="w-12 h-auto" alt="feature 2 icon" />
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature Two</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Brief description of another feature</span>
                                    </a>
                                </li>
                                <li class="w-full border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block h-full p-6 text-lg font-semibold hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/compass.png" class="w-12 h-auto" alt="feature 3 icon" />
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature Three</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Describe another one of your features here</span>
                                    </a>
                                </li>
                                <li class="w-full border-l border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block h-full p-6 text-lg font-semibold hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/lighthouse.png" class="w-12 h-auto" alt="feature 4 icon" />
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature Four</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Add a fourth feature or even a resource here</span>
                                    </a>
                                </li>
                                <li class="w-full border-l border-r border-gray-100 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block h-full p-6 text-lg font-semibold hover:bg-gray-50 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/chest.png" class="w-12 h-auto" alt="feature 5 icon" />
                                        <span class="block my-2 text-xs font-bold uppercase text-slate-800">Feature Five</span>
                                        <span class="block text-xs font-medium leading-5 text-slate-500">Add another feature highlight or link to a page</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li @mouseenter="showOverlay=true" @mouseleave="showOverlay=false" class="z-30 flex flex-col items-start h-auto md:h-full border-b border-gray-100 md:border-b-0 group md:flex-row md:items-center">
                        <a href="#_" class="flex items-center w-full hover:bg-gray-100 md:hover:bg-transparent h-16 gap-1 px-6 text-sm font-semibold text-gray-700 transition duration-300 md:h-full md:px-0 md:w-auto hover:text-gray-900">
                            <span class="">Resources</span>
                            <svg class="w-5 h-5 transition-all duration-300 ease-out group-hover:-rotate-180" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" class=""></path></svg>
                        </a>
                        <div class="top-0 left-0 invisible hidden w-screen space-y-3 transition-all duration-300 ease-out -translate-y-2 bg-white border-t border-b border-gray-100 shadow-sm opacity-0 md:block group-hover:block group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 md:absolute md:mt-24">
                            <ul class="flex flex-col justify-between max-w-6xl mx-auto md:flex-row md:px-12">
                                <div class="flex flex-col md:flex-row w-full border-l border-r divide-x divide-zinc-100 border-zinc-100">
                                    <div class="w-auto divide-y divide-zinc-100">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-neutral-100 group">
                                            <span class="block mb-1 font-medium text-black">Authentication</span>
                                            <span class="block font-light leading-5 opacity-50">Configure the login, register, and forgot password for your app</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-neutral-100 group">
                                            <span class="block mb-1 font-medium text-black">Roles and Permissions</span>
                                            <span class="block leading-5 opacity-50">We utilize the bullet-proof Spatie Permissions package</span>
                                        </a>
                                    </div>
                                    <div class="w-auto divide-y divide-zinc-100">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Posts and Pages</span>
                                            <span class="block font-light leading-5 opacity-50">Easily write blog articles and create pages for your application</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Themes</span>
                                            <span class="block leading-5 opacity-50">Kick-start your app with a pre-built theme or create your own</span>
                                        </a>
                                    </div>
                                    <div class="w-auto divide-y divide-zinc-100">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Settings and More</span>
                                            <span class="block leading-5 opacity-50">Easily create and update app settings. And so much more</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-neutral-100">
                                            <span class="block mb-1 font-medium text-black">Subscriptions</span>
                                            <span class="block leading-5 opacity-50">Integration payments and let users subscribe to a plan</span>
                                        </a>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </li>
                    <li class="h-16 flex-shrink-0 border-b border-gray-100 md:border-b-0 md:h-full">
                        <a href="{{ route('pricing') }}" class="flex md:px-0 px-6 items-center h-full hover:bg-gray-100 md:hover:bg-transparent text-sm font-semibold text-gray-700 transition duration-300 hover:text-gray-900">
                            Pricing
                        </a>
                    </li>
                    <li class="h-16 flex-shrink-0 border-b border-gray-100 md:border-b-0 md:h-full">
                        <a href="{{ route('blog') }}" class="flex md:px-0 px-6 items-center h-full hover:bg-gray-100 md:hover:bg-transparent text-sm font-semibold text-gray-700 transition duration-300 hover:text-gray-900">Blog</a>
                    </li>

                </ul>
            </nav>
            
            @guest
                <div class="relative z-30 items-center justify-center flex-shrink-0 hidden h-full space-x-3 text-sm md:flex">
                    <x-button href="{{ route('login') }}" tag="a" class="text-sm" color="secondary">Login</x-button>
                    <x-button href="{{ route('register') }}" tag="a" class="text-sm">Sign Up</x-button>
                </div>
            @else
                <x-button href="{{ route('login') }}" tag="a" class="text-sm" class="relative z-20 flex-shrink-0 ml-2">View Dashboard</x-button>
            @endguest

        </div>
    </x-container>

</header>
