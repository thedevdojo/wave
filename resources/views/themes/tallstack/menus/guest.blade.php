  <div class="hidden absolute relative top-0 left-0 z-20 justify-center items-center w-full md:block">
      <nav x-data="{
                navigationMenuOpen: false,
                navigationMenu: '',
                navigationMenuCloseDelay: 200,
                navigationMenuCloseTimeout: null,
                navigationMenuLeave() {
                    let that = this;
                    this.navigationMenuCloseTimeout = setTimeout(() => {
                    that.navigationMenuClose();
                    }, this.navigationMenuCloseDelay);
                },
                navigationMenuReposition(navElement) {
                    this.navigationMenuClearCloseTimeout();
                        console.log(this.$refs.navigationDropdown.offsetWidth/2);
                        this.$refs.navigationDropdown.style.left = navElement.offsetLeft + 'px';
                        console.log(navElement.offsetLeft);
                        this.$refs.navigationDropdown.style.marginLeft = (navElement.offsetWidth/2) + 'px';
                },
                    navigationMenuClearCloseTimeout(){
                    clearTimeout(this.navigationMenuCloseTimeout);
                    },
                    navigationMenuClose(){
                    this.navigationMenuOpen = false;
                    this.navigationMenu = '';
                }
            }" class="relative z-10 w-auto"
        >
            <div class="relative">
                <ul class="flex flex-1 justify-center items-center p-1 list-none rounded-md text-zinc-500 group">
                    <li class="px-0.5" @mouseover="navigationMenuOpen=true; navigationMenuReposition($el); navigationMenu='platform'" @mouseleave="navigationMenuLeave()">
                        <button :class="{ 'text-zinc-900 bg-zinc-100' : navigationMenu=='platform', 'hover:text-zinc-900' : navigationMenu!='platform' }" class="inline-flex justify-center items-center px-4 py-2 w-max h-10 text-sm font-medium rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:pointer-events-none group">
                            <span>Platform</span>
                            <svg :class="{ '-rotate-180' : navigationMenuOpen==true && navigationMenu == 'platform' }" class="relative top-[1px] ml-1 h-3 w-3 ease-out duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                    </li>
                    <li class="px-0.5" @mouseover="navigationMenuOpen=true; navigationMenuReposition($el); navigationMenu='resources'" @mouseleave="navigationMenuLeave()">
                        <button :class="{ 'text-zinc-900 bg-zinc-100' : navigationMenu=='resources', 'hover:text-zinc-900' : navigationMenu!='resources' }" class="inline-flex justify-center items-center px-4 py-2 w-max h-10 text-sm font-medium rounded-full transition-colors hover:text-neutral-900 focus:outline-none disabled:opacity-50 disabled:pointer-events-none group">
                            <span>Resources</span>
                            <svg :class="{ '-rotate-180' : navigationMenuOpen==true && navigationMenu == 'resources' }" class="relative top-[1px] ml-1 h-3 w-3 ease-out duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                    </li>
                    <li class="px-0.5">
                        <a href="#_" class="inline-flex justify-center items-center px-4 py-2 w-max h-10 text-sm font-medium rounded-full transition-colors hover:text-zinc-900 focus:outline-none disabled:opacity-50 disabled:pointer-events-none hover:bg-zinc-100 group">
                            Pricing
                        </a>
                    </li>
                    <li class="px-0.5">
                        <a href="#_" class="inline-flex justify-center items-center px-4 py-2 w-max h-10 text-sm font-medium rounded-full transition-colors hover:text-zinc-900 focus:outline-none disabled:opacity-50 disabled:pointer-events-none hover:bg-zinc-100 group">
                            Blog
                        </a>
                    </li>
                </ul>
            </div>
        <div x-ref="navigationDropdown" 
            x-show="navigationMenuOpen" 
            x-transition:enter="transition ease-out duration-300" 
            x-transition:enter-start="opacity-0 scale-[0.9] pointer-events-none translate-y-0" 
            x-transition:enter-end="opacity-100 scale-100 pointer-events-default translate-y-11" 
            x-transition:leave="transition ease-in duration-300" 
            x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
            x-transition:leave-end="opacity-0 scale-[0.9] translate-y-11" 
            @mouseover="navigationMenuClearCloseTimeout()" 
            @mouseleave="navigationMenuOpen=false" 
            class="absolute top-0 pt-4 duration-200 ease-out -translate-x-1/2 translate-y-11" 
        x-cloak>
            <div class="flex overflow-hidden justify-center w-auto h-auto bg-white rounded-2xl border shadow-sm border-neutral-200/70">
                <div x-show="navigationMenu == 'platform'" class="flex gap-x-3 justify-center items-stretch p-3 w-full max-w-7xl">
                    <div class="flex relative flex-col justify-center items-center p-10 w-48 h-full text-center bg-blue-600 rounded-xl">
                        <img src="{{ Storage::url('images/wave-white.png') }}" class="z-20 w-auto h-4 -translate-x-1.5">
                        <h3 class="z-30 mt-1 mt-4 text-xs font-normal text-blue-200">Start building your next great idea.</h3>
                        <a href="https://devdojo.com/wave" class="block relative items-center px-4 py-2 mt-5 w-full text-sm font-medium leading-5 text-center text-blue-500 bg-white rounded-full border border-transparent shadow-sm transition duration-150 ease-in-out hover:bg-zinc-100 focus:outline-none focus:border-zinc-300 focus:shadow-outline-gray active:bg-zinc-100">
                            Download
                        </a>
                    </div>
                    <div class="flex-shrink-0 w-72">
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100 group">
                            <span class="block mb-1 font-medium text-black">Authentication</span>
                            <span class="block font-light leading-5 opacity-50">Configure the login, register, and forgot password for your app</span>
                        </a>
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                            <span class="block mb-1 font-medium text-black">Roles and Permissions</span>
                            <span class="block leading-5 opacity-50">We utilize the bullet-proof Spatie Permissions package</span>
                        </a>
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                            <span class="block mb-1 font-medium text-black">Subscriptions</span>
                            <span class="block leading-5 opacity-50">Integration payments and let users subscribe to a plan</span>
                        </a>
                    </div>
                    <div class="flex-shrink-0 w-72">
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                            <span class="block mb-1 font-medium text-black">Posts and Pages</span>
                            <span class="block font-light leading-5 opacity-50">Easily write blog articles and create pages for your application</span>
                        </a>
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                            <span class="block mb-1 font-medium text-black">Themes</span>
                            <span class="block leading-5 opacity-50">Kick-start your app with a pre-built theme or create your own</span>
                        </a>
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                            <span class="block mb-1 font-medium text-black">Settings and More</span>
                            <span class="block leading-5 opacity-50">Easily create and update app settings. And so much more</span>
                        </a>
                    </div>
                </div>
                <div x-show="navigationMenu == 'resources'" class="flex justify-center items-stretch p-3 w-full">
                    <div class="w-72">
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                            <span class="block mb-1 font-medium text-black">Documentation</span>
                            <span class="block font-light leading-5 opacity-50">Learn how to setup, install, and configure Wave.</span>
                        </a>
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                            <span class="block mb-1 font-medium text-black">Videos</span>
                            <span class="block font-light leading-5 opacity-50">A series of video screencasts to help you get started.</span>
                        </a>
                        <a href="#_" @click="navigationMenuClose()" class="block px-3.5 py-3 text-sm rounded-xl hover:bg-neutral-100">
                            <span class="block mb-1 font-medium text-black">Blog</span>
                            <span class="block leading-5 opacity-50">Wave comes with a full blogging platform. See an example here. </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
      </nav>
  </div>

  <div class="hidden relative z-30 flex-shrink-0 justify-center items-center space-x-3 h-full text-sm md:flex">
      <a href="{{ route('login') }}" class="px-4 py-2 font-medium text-zinc-500 rounded-full hover:bg-zinc-100 whitespace-no-wrap hover:text-blue-600 focus:outline-none focus:text-zinc-900">
          Sign in
      </a>
      <span class="inline-flex rounded-md shadow-sm">
          <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-4 py-2 font-medium text-white bg-blue-500 rounded-full border border-transparent transition duration-150 ease-in-out whitespace-no-wrap hover:bg-blue-600 focus:outline-none focus:border-indigo-700 focus:shadow-outline-wave active:bg-blue-700">
              Sign up
          </a>
      </span>
  </div>
