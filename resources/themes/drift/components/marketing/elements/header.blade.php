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
        window.addEventListener('resize', function() {
            if(window.innerWidth > 768) {
                mobileMenuOpen = false;
            }
        });
        $watch('mobileMenuOpen', function(value){
            if(value){ document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); }
        });
        evaluateScrollPosition();
        window.addEventListener('scroll', function() {
            evaluateScrollPosition();
        })
    "
    :class="{ 'border-gray-200/60 bg-white/90 dark:bg-black backdrop-blur-lg h-16' : scrolled, 'border-transparent border-b bg-transparent translate-y-0' : !scrolled }"
    class="box-content sticky top-0 z-50 w-full h-16"
>
    <div
        x-show="showOverlay"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="absolute inset-0 w-full h-screen pt-16" x-cloak>
        <div class="w-screen h-full bg-black/50"></div>
    </div>
    <x-container>
        <div class="z-30 flex items-center justify-between h-16 md:space-x-8">
            <div class="z-20 flex items-center justify-between w-full md:w-auto">
                <div class="relative z-20 inline-flex">
                    <a href="{{ route('home') }}" class="flex items-center justify-center space-x-3 font-bold text-zinc-900">
                    <x-logo class="w-auto h-8 md:h-9"></x-logo>
                    </a>
                </div>
                <div class="relative flex space-x-1.5 lg:space-x-3 items-center">
                    <div class="relative flex items-center pl-5 space-x-1">
                        <x-marketing.elements.light-dark-button />
                    </div>
                    <div class="relative flex items-center space-x-1">
                        <button x-on:click="mobileMenuOpen=!mobileMenuOpen" class="relative flex items-center justify-center w-8 h-8 rounded-lg dark:text-gray-200 md:hidden">
                            <x-phosphor-x-bold x-show="mobileMenuOpen" class="w-4 h-4" />
                            <svg x-show="!mobileMenuOpen" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <nav :class="{ 'hidden' : !mobileMenuOpen, 'block md:relative absolute top-0 left-0 md:w-auto w-screen md:h-auto h-screen pointer-events-none md:z-10 z-10' : mobileMenuOpen }" class="h-full md:flex">
                <ul :class="{ 'hidden md:flex' : !mobileMenuOpen, 'flex flex-col absolute md:relative md:w-auto w-screen h-full md:h-full md:overflow-auto overflow-scroll md:pt-0 mt-16 md:pb-0 pb-48 bg-white dark:bg-black md:bg-transparent' : mobileMenuOpen }" id="menu" class="flex items-stretch justify-start flex-1 w-full h-full ml-0 pointer-events-auto md:items-center md:justify-center gap-x-8 md:w-auto md:border-t-0 md:flex-row">
                    <li x-data="{ open: false }" @mouseenter="showOverlay=true" @mouseleave="showOverlay=false" class="z-30 flex flex-col items-start h-auto md:h-full group md:flex-row md:items-center">
                        <a href="#_" x-on:click="open=!open" class="flex items-center w-full h-16 gap-1 text-sm font-semibold text-gray-700 transition duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 md:hover:bg-transparent md:dark:hover:bg-transparent px-7 md:h-full md:px-0 md:w-auto hover:text-gray-900">
                            <span class="">Platform</span>
                            <svg :class="{ 'group-hover:-rotate-180' : !mobileMenuOpen, '-rotate-180' : mobileMenuOpen && open }" class="w-5 h-5 transition-all duration-300 ease-out" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" class=""></path></svg>
                        </a>
                        <div
                            :class="{ 'hidden md:block opacity-0 invisible md:absolute' : !open, 'md:invisible md:opacity-0 md:hidden md:absolute' : open }"
                            class="top-0 left-0 w-screen space-y-3 transition-transform duration-300 ease-out bg-white border-gray-100 dark:bg-black md:border-t md:border-b dark:border-gray-700 md:shadow-md md:-translate-y-2 md:mt-16 md:block md:group-hover:block md:group-hover:visible md:group-hover:opacity-100 md:group-hover:translate-y-0" x-cloak>
                            <ul class="flex flex-col justify-between mx-auto max-w-7xl md:px-16 md:flex-row">
                                <li class="w-full border-gray-100 md:border-l dark:border-gray-700 md:w-1/5">
                                    <a href="#_" onclick="demoButtonClickMessage(event)" class="block h-full p-6 text-lg font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/anchor.png" class="w-12 h-auto" alt="feature 1 icon" />
                                        <span class="block my-2 text-xs font-bold text-gray-700 uppercase dark:text-gray-300">Feature One</span>
                                        <span class="block text-xs font-medium leading-5 text-gray-700 dark:text-gray-300">Highlight your main feature here</span>
                                    </a>
                                </li>
                                <li class="w-full border-gray-100 md:border-l dark:border-gray-700 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block h-full p-6 text-lg font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/turtle.png" class="w-12 h-auto" alt="feature 2 icon" />
                                        <span class="block my-2 text-xs font-bold text-gray-700 uppercase dark:text-gray-300">Feature Two</span>
                                        <span class="block text-xs font-medium leading-5 text-gray-700 dark:text-gray-300">Brief description of another feature</span>
                                    </a>
                                </li>
                                <li class="w-full border-gray-100 md:border-l dark:border-gray-700 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block h-full p-6 text-lg font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/compass.png" class="w-12 h-auto" alt="feature 3 icon" />
                                        <span class="block my-2 text-xs font-bold text-gray-700 uppercase dark:text-gray-300">Feature Three</span>
                                        <span class="block text-xs font-medium leading-5 text-gray-700 dark:text-gray-300">Describe another one of your features here</span>
                                    </a>
                                </li>
                                <li class="w-full border-gray-100 md:border-l dark:border-gray-700 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block h-full p-6 text-lg font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/lighthouse.png" class="w-12 h-auto" alt="feature 4 icon" />
                                        <span class="block my-2 text-xs font-bold text-gray-700 uppercase dark:text-gray-300">Feature Four</span>
                                        <span class="block text-xs font-medium leading-5 text-gray-700 dark:text-gray-300">Add a fourth feature or even a resource here</span>
                                    </a>
                                </li>
                                <li class="w-full border-gray-100 md:border-l md:border-r dark:border-gray-700 md:w-1/5">
                                    <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block h-full p-6 text-lg font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 lg:p-7 lg:py-10">
                                        <img src="/wave/img/icons/chest.png" class="w-12 h-auto" alt="feature 5 icon" />
                                        <span class="block my-2 text-xs font-bold text-gray-700 uppercase dark:text-gray-300">Feature Five</span>
                                        <span class="block text-xs font-medium leading-5 text-gray-700 dark:text-gray-300">Add another feature highlight or link to a page</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li x-data="{ open: false }" @mouseenter="showOverlay=true" @mouseleave="showOverlay=false" class="z-30 flex flex-col items-start h-auto border-gray-100 dark:border-gray-700 md:h-full md:border-b-0 group md:flex-row md:items-center">
                        <a href="#_" x-on:click="open=!open" class="flex items-center w-full h-16 gap-1 text-sm font-semibold text-gray-700 transition duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 md:hover:bg-transparent md:dark:hover:bg-transparent px-7 md:h-full md:px-0 md:w-auto hover:text-gray-900">
                            <span class="">Resources</span>
                            <svg :class="{ 'group-hover:-rotate-180' : !mobileMenuOpen, '-rotate-180' : mobileMenuOpen && open }" class="w-5 h-5 transition-all duration-300 ease-out" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" class=""></path></svg>
                        </a>
                        <div
                            :class="{ 'hidden md:block opacity-0 invisible md:absolute' : !open, 'md:invisible md:opacity-0 md:hidden md:absolute' : open }"
                            class="top-0 left-0 w-screen space-y-3 transition-transform duration-300 ease-out bg-white border-gray-100 dark:bg-black md:border-t md:border-b md:dark:border-gray-700 md:shadow-md md:-translate-y-2 md:mt-16 md:block md:group-hover:block md:group-hover:visible md:group-hover:opacity-100 md:group-hover:translate-y-0" x-cloak>
                            <ul class="flex flex-col justify-between mx-auto max-w-7xl md:flex-row md:px-12">
                                <div class="flex flex-col w-full md:border-l md:border-r md:divide-x md:flex-row divide-zinc-100 dark:divide-gray-700 border-zinc-100 dark:border-gray-700">
                                    <div class="w-auto md:divide-y divide-zinc-100 dark:divide-gray-700">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-gray-100 dark:hover:bg-gray-800 group">
                                            <span class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Authentication</span>
                                            <span class="block font-light leading-5 text-gray-700 opacity-50 dark:text-gray-300">Configure the login, register, and forgot password for your app</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-gray-100 dark:hover:bg-gray-800 group">
                                            <span class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Roles and Permissions</span>
                                            <span class="block leading-5 text-gray-700 opacity-50 dark:text-gray-300">We utilize the bullet-proof Spatie Permissions package</span>
                                        </a>
                                    </div>
                                    <div class="w-auto md:divide-y divide-zinc-100 dark:divide-gray-700">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <span class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Posts and Pages</span>
                                            <span class="block font-light leading-5 text-gray-700 opacity-50 dark:text-gray-300">Easily write blog articles and create pages for your application</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <span class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Themes</span>
                                            <span class="block leading-5 text-gray-700 opacity-50 dark:text-gray-300">Kick-start your app with a pre-built theme or create your own</span>
                                        </a>
                                    </div>
                                    <div class="w-auto md:divide-y divide-zinc-100 dark:divide-gray-700">
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <span class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Settings and More</span>
                                            <span class="block leading-5 text-gray-700 opacity-50 dark:text-gray-300">Easily create and update app settings. And so much more</span>
                                        </a>
                                        <a href="#_" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()" class="block text-sm p-7 hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <span class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Subscriptions</span>
                                            <span class="block leading-5 text-gray-700 opacity-50 dark:text-gray-300">Integration payments and let users subscribe to a plan</span>
                                        </a>
                                    </div>
                                </div>
                            </ul>
                        </div>

                    </li>
                    <li class="flex-shrink-0 h-16 border-gray-100 dark:border-gray-700 md:border-b-0 md:h-full">
                        <a href="{{ route('pricing') }}" class="flex items-center h-full text-sm font-semibold text-gray-700 transition duration-300 dark:text-gray-300 md:px-0 px-7 hover:bg-gray-100 dark:hover:bg-gray-800 md:hover:bg-transparent md:dark:hover:bg-transparent hover:text-gray-900">
                            Pricing
                        </a>
                    </li>
                    <li class="flex-shrink-0 h-16 border-gray-100 dark:border-gray-700 md:border-b-0 md:h-full">
                        <a href="{{ route('blog') }}" class="flex items-center h-full text-sm font-semibold text-gray-700 transition duration-300 dark:text-gray-300 md:px-0 px-7 hover:bg-gray-100 dark:hover:bg-gray-800 md:hover:bg-transparent md:dark:hover:bg-transparent hover:text-gray-900">Blog</a>
                    </li>

                    @guest
                        <li class="relative z-30 flex flex-col items-center justify-center flex-shrink-0 w-full h-auto pt-3 space-y-3 text-sm md:hidden px-7">
                            <x-button href="{{ route('login') }}" tag="a" class="w-full text-sm" color="secondary">Login</x-button>
                            <x-button href="{{ route('register') }}" tag="a" class="w-full text-sm">Sign Up</x-button>
                        </li>
                    @else
                        <li class="flex items-center justify-center w-full pt-3 md:hidden px-7">
                            <x-button href="{{ route('login') }}" tag="a" class="w-full text-sm">View Dashboard</x-button>
                        </li>
                    @endguest

                </ul>
            </nav>

            @guest
                <div class="relative z-30 items-center justify-center flex-shrink-0 hidden h-full space-x-3 text-sm md:flex">
                    <x-button href="{{ route('login') }}" tag="a" class="text-sm" color="secondary">Login</x-button>
                    <x-button href="{{ route('register') }}" tag="a" class="text-sm">Sign Up</x-button>
                </div>
            @else
                <x-button href="{{ route('login') }}" tag="a" class="text-sm" class="relative z-20 flex-shrink-0 hidden ml-2 md:block">View Dashboard</x-button>
            @endguest

        </div>
    </x-container>

</header>
