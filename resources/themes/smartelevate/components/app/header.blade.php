<section class="block w-full bg-white border-b border-gray-200 dark:border-gray-800 dark:bg-black" x-data="{ mobileNavOpen: false, menuItem: '' }" x-init="menuItem = window.location.pathname.replace(/^\/|\/$/g, '')">
    <x-app.container>
        <nav class="pt-3.5 pb-3.5 md:pb-0">
            <div class="flex items-center justify-between md:mb-2">
                <div class="w-auto px-0 px-1">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('home') }}" class="inline-flex items-center h-7">
                            <x-logo-icon class="w-auto h-8 text-black dark:text-white"></x-logo-icon>
                        </a>
                    </div>
                </div>
                <div class="w-full px-2 mr-auto md:w-auto">
                    <div class="flex items-center pr-4 text-gray-400 border border-gray-200 rounded-lg ring-1 ring-transparent focus-within:ring-black dark:bg-gray-800 dark:border-gray-900 focus-within:text-gray-900 dark:focus-within:text-gray-100 focus-within:border-gray-800">
                        <x-phosphor-magnifying-glass class="ml-2.5 w-4 h-4 fill-current translate-x-px" />
                        <input class="px-2 py-2 text-sm md:w-auto w-full text-gray-800 placeholder-gray-400 bg-transparent border-0 outline-none peer focus:outline-none focus:border-0 focus:ring-0 dark:text-gray-100" type="text" placeholder="Type to search">
                    </div>
                </div>
                
                <div class="hidden w-full pl-2 md:block md:w-auto">
                    <div class="items-center md:flex">
                        <div class="flex items-center mb-6 w-full text-[13px] text-gray-500 md:w-auto md:mb-0">
                            
                            <!-- Menu Links -->
                            <a href="#_" x-on:click="demoButtonClickMessage(event)" class="px-3 py-1.5 mr-1 rounded border border-gray-300 dark:border-gray-700 hover:text-gray-900 dark:text-gray-400 hover:dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none">Feedback</a>
                            <a href="{{ route('changelogs') }}" @class([ 'mx-3 border border-transparent hover:text-gray-900 dark:text-gray-400 hover:dark:text-gray-300 focus:outline-none', 'text-gray-900' => Request::is('changelog') ])>Changelog</a>
                            <a href="/dashboard/support" @class([ 'mx-3 border border-transparent hover:text-gray-900 dark:text-gray-400 hover:dark:text-gray-300 focus:outline-none', 'text-gray-900' => Request::is('dashboard/support') ])>Help</a>
                            <a href="https://devdojo.com/wave/docs" target="_blank" class="mx-3 border border-transparent hover:text-gray-900 dark:text-gray-400 hover:dark:text-gray-300 focus:outline-none">Docs</a>

                            <!-- Notification and Avatar -->
                            <a href="{{ route('notifications') }}" class="relative mr-4 cursor-pointer">
                                <x-phosphor-bell class="w-5 h-5" />
                                @if(auth()->user()->notifications()->count())
                                    <span class="absolute top-0 right-0 w-3 h-3 transform -translate-y-1 bg-red-600 border-2 border-white rounded-full translate-x-1/4"></span>
                                @endif
                            </a>
                        
                        </div>
                    </div>
                </div>
                <x-app.user-dropdown></x-app.user-dropdown>
                <div class="flex items-center h-full md:hidden">
                    <button x-on:click="mobileNavOpen = !mobileNavOpen" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none">
                        <x-phosphor-list-bold class="w-6 h-6" />
                    </button>
                </div>
            </div>
            <div 
                x-data="{ 
                    marker: null,
                    hovering: false,
                    setMarkerPosition(menuItem){
                        menuElement = document.querySelector(`[data-menu='` + menuItem + `']`);
                        if(menuElement){
                            console.log(menuElement.getBoundingClientRect().left);
                            console.log(menuElement.offsetLeft);
                            marker = $refs.marker;
                            marker.style.width = `${menuElement.offsetWidth}px`;
                            marker.style.height = `${menuElement.offsetHeight}px`;
                            marker.style.top = `${menuElement.offsetTop + parseInt(getComputedStyle($el).paddingTop)}px`;
                            marker.style.left = `${(menuElement.offsetLeft)}px`;
                        }
                    },
                    setActiveMarkerPosition(menuItem){
                        menuElement = document.querySelector(`[data-menu='` + menuItem.replace(/\//g, '-') + `']`);
                        if(menuElement){
                            marker = $refs.activeMarker;
                            marker.style.width = `${menuElement.offsetWidth}px`;
                            marker.style.height = `2px`;
                            marker.style.bottom = `0px`;
                            marker.style.left = `${(menuElement.offsetLeft)}px`;
                        }
                    }
                }" 
                x-init="
                    setMarkerPosition(window.location.pathname.replace(/^\/|\/$/g, '')); 
                    setActiveMarkerPosition(window.location.pathname.replace(/^\/|\/$/g, ''));
                    $watch('hovering', function(value){
                        if(value){
                            let that = this;
                            setTimeout(function(){
                                $refs.marker.classList.remove('transition-opacity');
                                $refs.marker.classList.add('duration-300', 'ease-out');
                            }, 10);
                        } else {
                            $refs.marker.classList.add('transition-opacity');
                            $refs.marker.classList.remove('duration-300', 'ease-out');
                        }
                        $nextTick(function(){
                            $refs.activeMarker.classList.remove('opacity-0');
                            setTimeout(function(){
                                
                            }, 10);
                        });
                    })" class="relative hidden pt-2 md:flex md:flex-wrap">
                <div x-ref="marker" :class="{ 'opacity-0' : !hovering, 'opacity-100' : hovering }" class="absolute transition-opacity left-0 rounded-lg bg-black bg-opacity-[7%] dark:bg-white dark:bg-opacity-15" x-cloak></div>
                <div x-ref="activeMarker" class="absolute bottom-0 w-0 h-0.5 bg-black opacity-100 duration-300 ease-out dark:bg-white" x-cloak></div>
                <div class="relative space-x-3" @mouseleave="hovering=false">
                    <x-app.header-menu-item link="/dashboard" text="Dashboard" />
                    <x-app.header-menu-item link="/projects" text="Projects" />
                    <x-app.header-menu-item link="/dashboard/deployments" text="Deployments" />
                    <x-app.header-menu-item link="/dashboard/domains" text="Domains" />
                    <x-app.header-menu-item link="/dashboard/analytics" text="Analytics" />
                    <x-app.header-menu-item link="/dashboard/support" text="Support" />
                    <x-app.header-menu-item link="/settings/profile" text="Settings" />
                </div>
            </div>
        </nav>
    </x-app.container>
    <div :class="{'block': mobileNavOpen, 'hidden': !mobileNavOpen}" class="fixed top-0 bottom-0 left-0 z-50 hidden">
        <div x-on:click="mobileNavOpen = !mobileNavOpen" class="fixed top-0 left-0 z-10 w-full h-full bg-gray-800 opacity-50"></div>
        <nav class="fixed z-20 w-full h-full bg-white dark:bg-gray-900 max-w-xss">
            <div class="flex flex-wrap items-center justify-between py-3.5 border-b border-gray-200 dark:border-gray-800">
                <x-app.container class="flex items-center justify-between w-full">
                    <div class="w-auto px-1">
                        <a class="inline-block h-full translate-y-[3px]" href="{{ route('home') }}">
                            <x-logo-icon class="w-auto h-8 text-black dark:text-white"></x-logo-icon>
                        </a>
                    </div>
                    <div x-data="{ url: window.location.pathname }" class="w-full px-2.5">
                        <x-filament::input.wrapper class="w-full" >
                            <x-filament::input.select x-model="url" x-on:change="if (url) window.location.href = url; console.log(url);">
                                <option value="/dashboard">Dashboard</option>
                                <option value="/projects">Projects</option>
                                <option value="/dashboard/deployments" text="Deployments">Deployments</option>
                                <option value="/dashboard/domains" text="Domains">Domains</option>
                                <option value="/dashboard/analytics" text="Analytics">Analytics</option>
                                <option value="/dashboard/support" text="Support">Support</option>
                                <option value="/settings/profile" text="Settings">Settings</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                    <button x-on:click="mobileNavOpen = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none">
                        <x-phosphor-x-bold class="w-6 h-6" />
                    </button>
                </x-app.container>
            </div>
            <div class="h-full py-5 overflow-y-auto">
                <x-app.container class="flex flex-col items-center justify-between w-full space-y-1 text-gray-600 dark:text-gray-200">

                        <a x-on:click="mobileNavOpen = false;" href="https://devdojo.com/wave/docs" target="_blank" class="flex items-center w-full px-3 py-2 rounded-md hover:bg-black/5 dark:hover:bg-white/10" href="#">
                            <x-phosphor-notebook-duotone class="w-5 h-5 mr-3" />
                            <span class="font-medium">Documentation</span>
                        </a>
                        <a x-on:click="mobileNavOpen = false" href="/dashboard/support" class="flex items-center w-full px-3 py-2 rounded-md hover:bg-black/5 dark:hover:bg-white/10" href="#">
                            <x-phosphor-question-duotone class="w-5 h-5 mr-3" />
                            <span class="font-medium">Help</span>
                        </a>
                        <a 
                            href="{{ route('changelogs') }}"
                            x-on:click="mobileNavOpen = false" class="flex items-center w-full px-3 py-2 rounded-md hover:bg-black/5 dark:hover:bg-white/10">
                            <x-phosphor-megaphone-duotone class="w-5 h-5 mr-3" />
                            <span class="font-medium">Changelog</span>
                        </a>
                        <a x-on:click="mobileNavOpen = false; demoButtonClickMessage(event)" class="flex items-center w-full px-3 py-2 rounded-md hover:bg-black/5 dark:hover:bg-white/10" href="#">
                            <x-phosphor-chat-text-duotone class="w-5 h-5 mr-3" />
                            <span class="font-medium">Feedback</span>
                        </a>
                        
                </x-app.container>
            </div>
        </nav>
    </div>
    
</section>