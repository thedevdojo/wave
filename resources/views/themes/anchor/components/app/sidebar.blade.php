<div x-data="{ mobileOpen: false }"  @open-sidebar.window="mobileOpen = true" class="relative w-screen md:w-auto" x-cloak>
    {{-- Backdrop for mobile --}}
    <div x-show="mobileOpen" @click="mobileOpen=false" class="fixed top-0 right-0 z-50 w-screen h-screen duration-300 ease-out bg-black/20 dark:bg-white/10"></div>
    
    {{-- Sidebar --}} 
    <div :class="{ '-translate-x-full': !mobileOpen }"
        class="fixed top-0 left-0 flex md:translate-x-0 flex-col z-50 justify-between h-screen overflow-x-hidden overflow-auto transition-[width,transform] duration-150 ease-out bg-white border-r shadow-sm dark:bg-zinc-900 items-between w-64 group border-slate-100 dark:border-zinc-800">  
        <div class="flex relative flex-col py-6">
            <div class="flex items-center px-4 space-x-2">
                <a href="/dashboard" wire:navigate class="flex justify-start items-center px-2.5 w-full h-auto brightness-0 duration-300 ease-out dark:brightness-[5] hover:brightness-100 dark:hover:brightness-100 min-w-16 group-hover:justify-start shrink-0">
                    <x-logo-darkmode class="hidden mb-2 w-auto h-4 fill-current dark:block text-zinc-800 dark:text-zinc-200" />
                    <x-logo class="block mb-2 w-auto h-4 fill-current dark:hidden text-zinc-800 dark:text-zinc-200" />
                </a>
            </div>
            <div class="flex items-center px-4 py-5">
                <div class="relative w-full rounded-md shadow-sm">
                    <x-phosphor-magnifying-glass class="absolute left-0 top-1/2 ml-2 w-5 h-5 text-gray-400 -translate-y-1/2" />
                    <input type="text" class="py-[7px] pl-8 w-full text-sm rounded-md border duration-50 dark:bg-zinc-950 ease border-zinc-200 dark:border-zinc-700/70 dark:ring-zinc-700/70 focus:ring dark:text-zinc-200 dark:focus:ring-zinc-700/70 dark:focus:border-zinc-700 focus:ring-zinc-200 focus:border-zinc-300 dark:placeholder-zinc-400" placeholder="Search">
                </div>
            </div>

            <div class="flex flex-col justify-start items-center px-4 space-y-1.5 w-full h-full text-slate-600 dark:text-zinc-400">
                <x-app.sidebar-link href="/dashboard" icon="phosphor-house" :active="Request::is('dashboard')">Dashboard</x-app.sidebar-link>
                <x-app.sidebar-dropdown text="Projects" icon="phosphor-stack" id="projects_dropdown" :active="(Request::is('projects'))" :open="(Request::is('project_a') || Request::is('project_b') || Request::is('project_c')) ? '1' : '0'">
                    <x-app.sidebar-link onclick="event.preventDefault(); new FilamentNotification().title('Modify this button inside of sidebar.blade.php').send()" icon="phosphor-cube" :active="(Request::is('project_a'))">Project A</x-app.sidebar-link>
                    <x-app.sidebar-link onclick="event.preventDefault(); new FilamentNotification().title('Modify this button inside of sidebar.blade.php').send()" icon="phosphor-cube" :active="(Request::is('project_b'))">Project B</x-app.sidebar-link>
                    <x-app.sidebar-link onclick="event.preventDefault(); new FilamentNotification().title('Modify this button inside of sidebar.blade.php').send()" icon="phosphor-cube" :active="(Request::is('project_c'))">Project C</x-app.sidebar-link>
                </x-app.sidebar-dropdown>
                <x-app.sidebar-link onclick="event.preventDefault(); new FilamentNotification().title('Modify this button inside of sidebar.blade.php').send()" icon="phosphor-pencil-line" active="false">Stories</x-app.sidebar-link>
                <x-app.sidebar-link  onclick="event.preventDefault(); new FilamentNotification().title('Modify this button inside of sidebar.blade.php').send()" icon="phosphor-users" active="false">Users</x-app.sidebar-link>
            </div>

            

        </div>

        <div class="relative px-2.5 pb-2.5 space-y-1.5 text-zinc-700 dark:text-zinc-400">
            
            <x-app.sidebar-link href="" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button inside of sidebar.blade.php').send()" icon="phosphor-book-bookmark-duotone" active="false">Documentation</x-app.sidebar-link>
            <x-app.sidebar-link href="" onclick="event.preventDefault(); new FilamentNotification().title('Modify this button inside of sidebar.blade.php').send()" icon="phosphor-lifebuoy-duotone" active="false">Help</x-app.sidebar-link>
            <x-app.sidebar-link :href="route('changelogs')" icon="phosphor-book-open-text-duotone" active="false">Changelog</x-app.sidebar-link>

            <div class="px-1 py-3">
                <div class="relative px-4 py-3 space-y-1 w-full bg-white rounded-lg border text-zinc-700 dark:text-zinc-100 dark:bg-zinc-800 border-zinc-100 dark:border-zinc-700">
                    <button class="absolute top-0 right-0 mt-3.5 mr-3.5 w-3.5 h-3.5 text-zinc-500 dark:text-zinc-400">
                        <x-phosphor-x-bold class="w-full h-full" />
                    </button>
                    <h5 class="text-sm font-bold">Progress</h5>
                    <p class="block pb-3 text-xs opacity-80">You've completed 90% of setup</p>
                    <div class="overflow-hidden relative w-full h-2 rounded-full bg-zinc-300 dark:bg-zinc-600">
                        <div class="bg-blue-500 h-full w-[90%] absolute left-0 top-0"></div>
                    </div>
                    <div class="flex justify-start items-center pt-3 space-x-2 font-medium">
                        <span class="text-xs cursor-pointer text-zinc-500 dark:text-zinc-300 hover:underline">Dismiss</span>
                        <span class="ml-auto text-xs text-blue-500 cursor-pointer dark:text-blue-400 hover:underline">Complete</span>
                    </div>
                </div>
            </div>

            <div class="my-2 w-full h-px bg-slate-100 dark:bg-zinc-700"></div>
            <div x-data="{ dropdownOpen: false }"
                :class="{ 'block z-50 w-full p-4 bg-white dark:bg-zinc-900 dark:border-zinc-800' : open, 'hidden': ! open }"
                class="relative flex-shrink-0 sm:p-0 sm:flex sm:w-auto sm:bg-transparent sm:items-center"
                x-cloak
            >
                <button @click="dropdownOpen=!dropdownOpen" class="flex p-2 w-full text-[13px] hover:bg-zinc-100 rounded-md justify-start items-center w-full hover:text-black dark:hover:text-zinc-100 dark:hover:bg-zinc-700/60 space-x-1.5 overflow-hidden group-hover:autoflow-auto items">
                    <x-phosphor-user-circle-duotone class="flex-shrink-0 w-5 h-5" />
                    <span class="flex-shrink-0 ease-out duration-50">{{ Auth::user()->name }}</span>
                    <svg class="absolute right-0 w-4 h-4 ease-out rotate-180 -translate-x-2 fill-current group-hover:delay-150 duration-0 group-hover:duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
                <div wire:ignore x-show="dropdownOpen" @mouse.leave="dropdownOpen=false" @click.away="dropdownOpen=false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 sm:scale-95" x-transition:enter-end="transform opacity-100 sm:scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 sm:scale-100" x-transition:leave-end="transform opacity-0 sm:scale-95" 
                    class="absolute bottom-0 left-0 z-50 mb-12 w-full sm:mt-12 sm:origin-bottom sm:w-full" x-cloak>
                    <div class="pt-0 mt-1 bg-white text-zinc-600 dark:text-white/70 dark:bg-zinc-900 dark:shadow-xl sm:space-y-0.5 sm:border sm:shadow-md sm:rounded-md border-zinc-200/70 dark:border-white/10">
                        <div class="px-[18px] h-11 flex items-center text-[13px] font-bold">{{ auth()->user()->email }}</div>
                        <div class="my-2 w-full h-px bg-slate-100 dark:bg-zinc-700"></div>
                        <div class="relative px-2 py-1">
                            <x-app.light-dark-toggle></x-app.light-dark-toggle>
                        </div>
                        <div class="my-2 w-full h-px bg-slate-100 dark:bg-zinc-700"></div>
                        <div class="flex relative flex-col p-2 space-y-1">
                            <x-app.sidebar-link :hideUntilGroupHover="false" href="{{ route('notifications') }}" icon="phosphor-bell-duotone" active="false">Notifications</x-app.sidebar-link>
                            <x-app.sidebar-link :hideUntilGroupHover="false" href="{{ route('wave.settings', 'profile') }}" icon="phosphor-gear-duotone" active="false">Edit Profile</x-app.sidebar-link>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button onclick="event.preventDefault(); this.closest('form').submit();" class="relative w-full flex cursor-pointer hover:text-zinc-700 dark:hover:text-zinc-100 select-none hover:bg-zinc-200 dark:hover:bg-zinc-700/60 items-center rounded-md p-2 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                                    <x-phosphor-sign-out-duotone class="flex-shrink-0 mr-2 ml-1 w-auto h-5" />
                                    <span>Log out</span>
                                </button>
                            </form>
                            @impersonating
                            <x-app.sidebar-link href="{{ route('impersonate.leave') }}" icon="phosphor-user-circle-duotone" active="false">Leave impersonation</x-app.sidebar-link>
                            @endImpersonating
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
