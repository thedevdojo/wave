<div x-data="{ sidebarOpen: false }"  @open-sidebar.window="sidebarOpen = true" class="relative z-50 w-screen md:w-auto" x-cloak>
    {{-- Backdrop for mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed top-0 right-0 z-50 w-screen h-screen duration-300 ease-out bg-black/20 dark:bg-white/10"></div>
    
    {{-- Sidebar --}} 
    <div :class="{ '-translate-x-full': !sidebarOpen }"
        class="fixed top-0 left-0 flex -translate-x-full lg:translate-x-0 flex-col pt-4 pb-2.5 z-50 justify-between h-screen overflow-x-hidden overflow-auto transition-[width,transform] duration-150 ease-out bg-zinc-50 dark:bg-zinc-900 items-between w-64 group @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">  
        <div class="flex relative flex-col">
            <button x-on:click="sidebarOpen=false" class="h-10 ml-4 w-10 flex lg:hidden items-center text-zinc-400 hover:text-zinc-800 justify-center flex-shrink-0 hover:bg-gray-200/70 rounded-md">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>

            <div class="flex items-center px-5 space-x-2">
                <a href="/dashboard" wire:navigate class="flex justify-center items-center py-4 pl-0.5 space-x-1 font-bold text-zinc-900">
                    <x-logo class="w-auto h-7" />
                </a>
            </div>
            <div class="flex items-center px-4 pb-3 pt-1">
                <div class="flex relative items-center w-full h-full rounded-lg">
                    <x-phosphor-magnifying-glass class="absolute left-0 ml-2 w-5 h-5 text-gray-400 -translate-y-px" />
                    <input type="text" class="py-2 bg-zinc-200/70 focus:bg-white pl-8 w-full text-sm rounded-lg border duration-50 dark:bg-zinc-950 ease border-zinc-200 dark:border-zinc-700/70 dark:ring-zinc-700/70 focus:ring dark:text-zinc-200 dark:focus:ring-zinc-700/70 dark:focus:border-zinc-700 focus:ring-zinc-200 focus:border-zinc-300 dark:placeholder-zinc-400" placeholder="Search">
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

        <div class="relative px-2.5 space-y-1.5 text-zinc-700 dark:text-zinc-400">
            
            <x-app.sidebar-link href="https://devdojo.com/wave/docs" target="_blank" icon="phosphor-book-bookmark-duotone" active="false">Documentation</x-app.sidebar-link>
            <x-app.sidebar-link href="https://devdojo.com/questions" target="_blank" icon="phosphor-chat-duotone" active="false">Questions</x-app.sidebar-link>
            <x-app.sidebar-link :href="route('changelogs')" icon="phosphor-book-open-text-duotone" active="false">Changelog</x-app.sidebar-link>

            <div x-show="sidebarTip" x-data="{ sidebarTip: $persist(true) }" class="px-1 py-3" x-collapse x-cloak>
                <div class="relative px-4 py-3 space-y-1 w-full rounded-lg border bg-zinc-50 text-zinc-700 dark:text-zinc-100 dark:bg-zinc-800 border-zinc-200/60 dark:border-zinc-700">
                    <button @click="sidebarTip=false" class="absolute top-0 right-0 z-50 p-1.5 mt-2.5 mr-2.5 rounded-full opacity-80 cursor-pointer hover:opacity-100 hover:bg-zinc-100 hover:dark:bg-zinc-700 hover:dark:text-zinc-300 text-zinc-500 dark:text-zinc-400">
                        <x-phosphor-x-bold class="w-3 h-3" />
                    </button>
                    <h5 class="pb-1 text-sm font-bold -translate-y-0.5">Edit This Section</h5>
                    <p class="block pb-1 text-xs opacity-80 text-balance">You can edit any aspect of your user dashboard. This section can be found inside your theme component/app/sidebar file.</p>
                    
                </div>
            </div>

            <div class="my-2 w-full h-px bg-slate-100 dark:bg-zinc-700"></div>
            <x-app.user-menu />
        </div>
    </div>
</div>
