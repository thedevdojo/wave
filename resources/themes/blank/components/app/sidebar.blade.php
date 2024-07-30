<aside class="flex fixed flex-col flex-shrink-0 justify-between w-64 h-screen bg-white border-r border-black">
    <div class="flex flex-col items-start p-7">
        <a href="/dashboard">
            <x-logo class="mb-6 w-auto h-7"></x-logo>
        </a>
        <nav class="flex flex-col -mx-1 space-y-2 w-full">
            <x-app.sidebar-link href="/dashboard" active="true">Menu Item 1</x-app.sidebar-link>
            <x-app.sidebar-link onclick="sidebarLinkDemo(event)">Menu Item 2</x-app.sidebar-link>
            <x-app.sidebar-dropdown text="Menu Dropdown" icon="phosphor-stack" id="example_dropdown" :active="false" :open="false">
                <x-app.sidebar-link onclick="sidebarLinkDemo(event)">Dropdown Item 1</x-app.sidebar-link>
                <x-app.sidebar-link onclick="sidebarLinkDemo(event)">Dropdown Item 2</x-app.sidebar-link>
                <x-app.sidebar-link onclick="sidebarLinkDemo(event)">Dropdown Item 3</x-app.sidebar-link>
                <x-app.sidebar-link onclick="sidebarLinkDemo(event)">Dropdown Item 4</x-app.sidebar-link>
            </x-app.sidebar-dropdown>
            <x-app.sidebar-link onclick="sidebarLinkDemo(event)">Final Menu Item</x-app.sidebar-link>
        </nav>
    </div>
    <div class="relative px-6 py-4">
        <div class="relative pb-4 -mx-1 space-y-1">
            <x-app.sidebar-link target="_blank" href="https://devdojo.com/wave/docs">Documentation</x-app.sidebar-link>
            <x-app.sidebar-link href="/changelog">Changelog</x-app.sidebar-link>
        </div>
        <div x-data="{ dropdownOpen: false }"
            :class="{ 'block z-50 w-full p-4 bg-white dark:bg-gray-900 dark:border-gray-800' : open, 'hidden': ! open }"
            class="relative flex-shrink-0 sm:p-0 sm:flex sm:w-auto sm:bg-transparent sm:items-center"
            x-cloak
        >
            <button @click="dropdownOpen=!dropdownOpen" class="flex px-3 py-2.5 w-full text-[13px] hover:bg-gray-100 justify-start items-center w-full hover:text-black border border-black space-x-1.5 overflow-hidden group-hover:autoflow-auto items">
                {{-- <x-phosphor-user-circle-duotone class="flex-shrink-0 w-5 h-5" /> --}}
                <img x-data="{ src: '', refreshAvatarSrc(){ this.src='{{ auth()->user()->avatar() }}' + '?' + new Date().getTime() } }" x-init="refreshAvatarSrc()" @refresh-avatar.window="refreshAvatarSrc()" :src="src" class="w-5 h-5 border border-black" alt="{{ auth()->user()->name }}" x-cloak />
                <span class="flex-shrink-0 ease-out duration-50">{{ Auth::user()->name }}</span>
                <svg class="absolute right-0 w-4 h-4 ease-out rotate-180 -translate-x-2 fill-current group-hover:delay-150 duration-0 group-hover:duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
            <div wire:ignore x-show="dropdownOpen" @mouse.leave="dropdownOpen=false" @click.away="dropdownOpen=false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 sm:scale-95" x-transition:enter-end="transform opacity-100 sm:scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 sm:scale-100" x-transition:leave-end="transform opacity-0 sm:scale-95" 
                class="absolute bottom-0 left-0 z-50 mb-12 w-full sm:mt-12 sm:origin-bottom sm:w-full" x-cloak>
                <div class="pt-0 mt-1 text-gray-600 bg-white border-black sm:space-y-0.5 sm:border">
                    <div class="py-4 px-5 text-[13px] font-bold text-ellipsis overflow-hidden whitespace-nowrap">{{ auth()->user()->email }}</div>
                    <div class="my-2 w-full h-px bg-slate-100 dark:bg-gray-700"></div>
                    <div class="flex relative flex-col p-2 space-y-1">
                        <x-app.sidebar-link href="/notifications">Notifications</x-app.sidebar-link>
                        <x-app.sidebar-link href="{{ '/profile/' . auth()->user()->username }}">Public Profile</x-app.sidebar-link>
                        <x-app.sidebar-link href="{{ route('settings.profile') }}">Settings</x-app.sidebar-link>
                        @if(auth()->user()->isAdmin())
                            <x-app.sidebar-link :ajax="false" href="/admin">View Admin</x-app.sidebar-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button onclick="event.preventDefault(); this.closest('form').submit();" class="relative w-full flex cursor-pointer hover:text-gray-700 select-none hover:bg-gray-100 font-medium items-center px-3 py-2.5 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
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
</aside>