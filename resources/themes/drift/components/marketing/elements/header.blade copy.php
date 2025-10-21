<header 
    x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false, 
        showOverlay: false, 
        topOffset: '5',
        navigationMenuOpen: false,
        navigationMenu: '',
        navigationMenuCloseDelay: 200,
        navigationMenuCloseTimeout: null,
        navigationMegaMenuActiveElement: null,
        navElementButton: null,
        isMobile: false,
        navigationMenuLeave() {
            let that = this;
            this.navigationMenuCloseTimeout = setTimeout(() => {
                that.navigationMenuClose();
            }, this.navigationMenuCloseDelay);
        },
        navigationMenuReposition(navElement) {
            this.navigationMenuClearCloseTimeout();
            this.navElementButton = navElement;
            this.navigationMegaMenuActiveElement = $refs[navElement.dataset.ref];

            this.$refs.navigationDropdown.style.left = navElement.offsetLeft + 'px';

            let that = this;
            setTimeout(function(){
                that.calculateMegMenuWidthAndHeight();
            }, 10);

            this.calculateMegMenuWidthAndHeight();
        },
        calculateMegMenuWidthAndHeight(){
            if(this.navigationMegaMenuActiveElement){
                let newWidth = parseInt(getComputedStyle(this.navigationMegaMenuActiveElement).width);
                let newHeight = parseInt(getComputedStyle(this.navigationMegaMenuActiveElement).height);

                if(this.isMobile){
                    //this.$refs.navigationDropdown.style.top = '0px';//this.navElementButton.offsetTop + 'px';
                    this.$refs.navigationDropdown.style.width = '100%';
                    this.$refs.navigationDropdown.style.height = '100vh';
                } else {
                    console.log(this.navElementButton.offsetTop);
                    this.$refs.navigationDropdown.style.width = newWidth + 'px';
                    this.$refs.navigationDropdown.style.height = newHeight + 20 + 'px';
                }
            }
        },
        navigationMenuClearCloseTimeout(){
            console.log('clearing');
            clearTimeout(this.navigationMenuCloseTimeout);
        },
        navigationMenuClose(){
            console.log('menu closed');
            this.navigationMenuOpen = false;
        },
        evaluateScrollPosition(){
            if(window.pageYOffset > this.topOffset){
                this.scrolled = true;
            } else {
                this.scrolled = false;
            }
        },
        evaluateMobileSize(){
             if(window.innerWidth >= 768){
                this.isMobile = false;
            } else {
                this.isMobile = true;
            }
        },
        openMegaMenu(element, menuName){
            this.navigationMenuOpen=true; 
            this.navigationMenuReposition(element); 
            this.navigationMenu=menuName;
        }
    }" 
    
    x-init="
        evaluateScrollPosition();
        window.addEventListener('scroll', function() {
            evaluateScrollPosition(); 
        })

        window.addEventListener('resize', function() {
           evaluateMobileSize();
        });
        evaluateMobileSize();

        $watch('mobileMenuOpen', function(value){
            if(value){ document.body.classList.add('overflow-hidden'); }
            else{ document.body.classList.remove('overflow-hidden'); }
        })
    " 
    :class="{ 'border-gray-600/10 dark:border-white/10 duration-300 ease-out backdrop-blur-md ' : scrolled, 'border-transparent dark:border-transparent bg-transparent' : !scrolled }" class="sticky top-0 z-50 w-full h-16 border-b border-transparent bg-white/95 dark:bg-black/90">
    <div class="relative flex items-center justify-between px-5 md:px-8 py-4 mx-auto @if(!Request::is('/')){{ 'max-w-7xl' }}@else{{ '2xl:container' }}@endif">
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="text-xl font-semibold text-gray-800">
                <x-logo class="w-auto h-7" />
            </a>
            <div 
                :class="{ 'block' : mobileMenuOpen, 'md:block hidden' : !mobileMenuOpen }"
                class="z-10 hidden w-auto md:ml-3 lg:ml-5 md:block">
                <div :class="{ 'fixed md:relative bg-white dark:bg-black md:bg-transparent md:min-h-0 min-h-screen md:w-auto w-screen left-0 ease-out border-t border-gray-200 md:border-t-0 dark:border-gray-800 overflow-scroll md:overflow-auto duration-300 top-16 md:top-0 p-3 md:p-0' : mobileMenuOpen, 'h-0 md:h-auto' : !mobileMenuOpen }">
                    <div class="block md:hidden">
                        <x-marketing.elements.header-auth-dashboard-buttons />
                    </div>
                    <nav class="relative">
                        <ul class="flex flex-col space-x-0 md:space-y-0 space-y-0.5 md:flex-row lg:space-x-1 group dark:text-neutral-300">
                            <li><x-marketing.elements.nav-item dropdown="true" dropdown-ref="products">Products</x-marketing.elements.nav-item></li>
                            <li><x-marketing.elements.nav-item dropdown="true" dropdown-ref="solutions">Solutions</x-marketing.elements.nav-item></li>
                            <li><x-marketing.elements.nav-item dropdown="true" dropdown-ref="resources">Resources</x-marketing.elements.nav-item></li>
                            <li><x-marketing.elements.nav-item href="{{ route('blog') }}">Blog</x-marketing.elements.nav-item></li>
                            <li><x-marketing.elements.nav-item href="/pricing">Pricing</x-marketing.elements.nav-item></li>
                        </ul>
                    </nav>
                    <div x-show="navigationMenuOpen" class="fixed inset-0 top-0 left-0 block w-screen h-screen md:hidden bg-black/20"></div>
                    <div 
                        x-on:click="navigationMenuClose()"
                        x-show="navigationMenuOpen"
                        x-transition:enter="transition ease-out delay-300 duration-100" 
                        x-transition:enter-start="opacity-0 h-0 md:scale-90" 
                        x-transition:enter-end="opacity-100 h-7 scale-100" 
                         class="fixed top-0 right-0 z-40 flex items-center justify-center w-8 h-8 mt-5 mr-5 space-x-1 text-sm text-gray-500 rounded-lg cursor-pointer hover:text-gray-800 dark:hover:text-gray-300 md:hidden hover:bg-black/10">
                        <x-phosphor-x-bold class="w-4 h-4" />
                    </div>
                    <div x-ref="navigationDropdown" x-show="navigationMenuOpen" 
                        x-transition:enter="transition ease-out origin-top duration-100" 
                        x-transition:enter-start="opacity-0 md:translate-y-12 translate-y-full -mt-1 md:scale-90" 
                        x-transition:enter-end="opacity-100 mt-1.5 md:mt-0 scale-100" 
                        x-transition:leave="transition ease-out origin-bottom md:origin-top duration-100" 
                        x-transition:leave-start="opacity-100 scale-100" 
                        x-transition:leave-end="opacity-0 md:scale-90" 
                        @mouseover="if(!isMobile){ navigationMenuClearCloseTimeout(); }" 
                        @mouseleave="if(!isMobile){ navigationMenuLeave(); }" 
                        :class="{ 'fixed h-full' : mobileMenuOpen, 'absolute' : !mobileMenuOpen }" 
                        class="top-0 left-0 flex items-stretch w-screen px-2 py-2 overflow-hidden duration-300 md:pt-3 md:pb-0 md:px-0 md:translate-y-12 md:translate-x-2 lg:translate-x-4 md:block justify-stretch md:w-auto" x-cloak>
                        <div x-show="navigationMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-out duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="absolute top-0 left-0 z-40 hidden w-4 h-4 mt-2 -translate-y-px md:block ml-36">
                            <div class="w-4 h-4 rotate-45 bg-white border border-gray-200 rounded dark:bg-gradient-to-b dark:from-gray-950 dark:to-black dark:border-gray-800"></div>
                        </div>
                        <div x-show="navigationMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-out duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="absolute md:block hidden top-0 left-0 z-50 mt-2 ml-36 w-[18px] h-[18px] -translate-y-px">
                            <div class="w-[18px] h-[18px] bg-white dark:bg-gray-900 rounded border border-transparent rotate-45 -translate-x-px translate-y-0.5"></div>
                        </div>
                        
                        <div class="relative z-40 flex justify-center w-full h-auto overflow-scroll overflow-x-hidden bg-white border border-gray-200 shadow-sm dark:border-gray-700 dark:bg-gray-900 scrollbar-hidden md:overflow-hidden md:w-auto rounded-xl">
                            <div x-ref="products" :class="{ 'translate-x-0 z-20' : navigationMenu == 'products', 'md:-translate-x-1/2 opacity-0 z-10 absolute' : navigationMenu != 'products' }" class="flex flex-col duration-300 ease-out w-full md:w-[628px] p-7 md:p-5 pb-0 md:pb-[138px]">
                                <div class="flex flex-col items-stretch justify-center md:flex-row md:gap-x-3">
                                    <div class="flex-shrink-0 pb-1 w-72">
                                        <h3 class="mb-4 text-xs font-semibold leading-6 text-gray-900 uppercase dark:text-gray-300">User Management</h3>
                                        <div class="space-y-5">
                                            <x-marketing.elements.mega-menu-link icon="phosphor-lock-key" title="Authentication" description="User login, registration, and more" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-key" title="Roles & Permissions" description="Define roles and permission" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-user-switch" title="User Impersonations" description="Impersonate users in your app" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 pb-1 mt-6 w-72 md:mt-0">
                                        <h3 class="mb-4 text-xs font-semibold leading-6 text-gray-900 uppercase dark:text-gray-300">Billing</h3>
                                        <div class="space-y-5">
                                            <x-marketing.elements.mega-menu-link icon="phosphor-bank" title="Payments" description="User login, registration, and more" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-credit-card" title="Subscriptions" description="Define roles and permission" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-shopping-bag-open" title="Plans" description="Impersonate users in your app" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 flex-shrink-0 hidden w-full px-8 py-6 mt-0 md:block bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:border-t dark:border-gray-700 dark:to-gray-800">
                                    <div class="flex items-center gap-x-3">
                                        <h3 class="text-sm font-semibold leading-5 text-gray-900 dark:text-gray-100">Advanced Features</h3>
                                        <p class="px-2.5 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-600 dark:text-white rounded-full bg-indigo-600/10">New</p>
                                    </div>
                                    <p class="mt-2 text-xs leading-5 text-gray-500 dark:text-gray-500">Subscribe to any plan and gain access to all our advanced features, which provide you with next-level capabilities, elite performance, and top-class reliability.</p>
                                </div>
                            </div>
                            <div x-ref="solutions" :class="{ 'translate-x-0 z-20' : navigationMenu == 'solutions', 'md:translate-x-1/2 opacity-0 z-10 absolute' : navigationMenu == 'products', 'md:-translate-x-1/2 opacity-0 z-10 absolute' : navigationMenu == 'resources' }" class="flex justify-center items-stretch p-7 pb-10 md:pb-7 md:p-5 w-full md:w-[552px] duration-300 ease-out">
                                <div class="relative flex flex-col items-stretch justify-start w-full sm:justify-center md:flex-row">
                                    <div class="flex-shrink-0 w-full pb-1 md:w-64">
                                        <h3 class="mb-4 text-xs font-semibold leading-6 text-gray-900 uppercase dark:text-gray-300">SaaS Apps</h3>
                                        <div class="space-y-5">
                                            <x-marketing.elements.mega-menu-link icon="phosphor-pencil-line" title="Form Builder" description="Custom form creation" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-check-square-offset" title="Project Hub" description="Project management app" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-video" title="Video Platform" description="Video subscription platform" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-lifebuoy" title="Help Desk" description="Help desk system" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 w-full pb-1 mt-6 md:mt-0 md:w-64">
                                        <h3 class="mb-4 text-xs font-semibold leading-6 text-gray-900 uppercase dark:text-gray-300">AI SaaS Apps</h3>
                                        <div class="space-y-5">
                                            <x-marketing.elements.mega-menu-link icon="phosphor-code-block" title="Code Review" description="Automated Code Review" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-note" title="Blog/Post Writer" description="Write Posts with AI" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-envelope-simple" title="Email Organizer" description="Organize Email with AI" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                            <x-marketing.elements.mega-menu-link icon="phosphor-layout" title="Design AI" description="Create Designs with AI" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div :class="{ 'translate-x-0 z-20' : navigationMenu == 'resources', 'md:translate-x-1/2 opacity-0 z-10 absolute' : navigationMenu != 'resources' }" x-ref="resources" class="flex justify-start md:justify-center w-full md:w-[328px] items-stretch p-5 duration-300 ease-out">
                                <div class="flex-shrink-0 w-full pb-1 md:w-72">
                                    <div class="space-y-5">
                                        <x-marketing.elements.mega-menu-link icon="phosphor-newspaper" title="Documentation" description="View our developer docs" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                        <x-marketing.elements.mega-menu-link icon="phosphor-chat-circle-text" title="Community" description="Visit our community" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                        <x-marketing.elements.mega-menu-link icon="phosphor-notebook" title="Changelog" description="See what's happening" onclick="demoButtonClickMessage(event)"></x-marketing.elements.mega-menu-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative flex space-x-1.5 lg:space-x-3 items-center">
            <div class="relative flex items-center space-x-1">
                <x-marketing.elements.light-dark-button />
                <button x-on:click="mobileMenuOpen=!mobileMenuOpen" class="relative flex items-center justify-center w-8 h-8 rounded-lg dark:text-gray-200 dark:hover:bg-gray-800 hover:bg-gray-100 md:hidden">
                    <x-phosphor-x-bold x-show="mobileMenuOpen" class="w-4 h-4" />
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
                </button>
            </div>
            
            <div class="items-center hidden md:flex">
                <x-marketing.elements.header-auth-dashboard-buttons />
            </div>
        </div>
    </div>
</header>