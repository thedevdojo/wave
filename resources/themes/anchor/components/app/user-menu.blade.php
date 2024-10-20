@props([
    'position' => 'bottom'
])
<div x-data="{ dropdownOpen: false }" :class="{ 'block z-50 w-auto lg:w-full dark:bg-zinc-900 dark:border-zinc-800' : open, 'hidden': ! open }" class="relative flex-shrink-0 sm:p-0 dark:text-zinc-200 sm:flex sm:w-auto sm:bg-transparent sm:items-center" x-cloak>
    <button @click="dropdownOpen=!dropdownOpen" class="flex p-2.5 lg:p-2 w-full space-x-1 text-[13px] hover:bg-zinc-200/70 rounded-lg justify-between items-center w-full hover:text-black dark:hover:text-zinc-100 dark:hover:bg-zinc-700/60 space-x-1.5 overflow-hidden group-hover:autoflow-auto items">
        <span class="relative flex items-center space-x-2">
            <x-avatar src="{{ auth()->user()->avatar() }}" alt="{{ auth()->user()->name }} photo" size="2xs" />
            <span @class([
                'flex-shrink-0 ease-out duration-50',
                'hidden' => ($position != 'bottom')
            ])>{{ Auth::user()->name }}</span>
        </span>
        <svg :class="{ 'rotate-180' : '{{ $position }}' == 'bottom' }" class="relative right-0 w-4 h-4 ease-out mr-4 -translate-x-0.5 fill-current group-hover:delay-150 duration-0 group-hover:duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
    </button>
    <div wire:ignore x-show="dropdownOpen" @mouse.leave="dropdownOpen=false" @click.away="dropdownOpen=false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 sm:scale-95" x-transition:enter-end="transform opacity-100 sm:scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 sm:scale-100" x-transition:leave-end="transform opacity-0 sm:scale-95" 
        @class([
            'z-50',
            'left-0  absolute w-full bottom-0 sm:origin-bottom mb-12' => ($position == 'bottom'),
            'top-0 sm:origin-top right-0 mr-5 mt-14 w-full max-w-xs fixed' => ($position != 'bottom')
        ]) 
        x-cloak>
        <div class="pt-0 mt-1 bg-white border dark:border-zinc-700 text-zinc-600 dark:text-white/70 dark:bg-zinc-900 dark:shadow-xl sm:space-y-0.5 sm:border shadow-md rounded-xl border-zinc-200/70 dark:border-white/10">
            <div class="px-[18px] py-3.5 text-[13px] font-bold text-ellipsis overflow-hidden whitespace-nowrap">{{ auth()->user()->email }}</div>
            <div class="w-full h-px my-2 bg-slate-100 dark:bg-zinc-700"></div>
            <div class="relative px-2 py-1">
                <x-app.light-dark-toggle></x-app.light-dark-toggle>
            </div>
            <div class="w-full h-px my-2 bg-slate-100 dark:bg-zinc-700"></div>
            <div class="relative flex flex-col p-2 space-y-1">
                <x-app.sidebar-link :hideUntilGroupHover="false" href="{{ route('notifications') }}" icon="phosphor-bell-duotone" active="false">Notifications</x-app.sidebar-link>
                <x-app.sidebar-link :hideUntilGroupHover="false" href="{{ '/profile/' . auth()->user()->username }}" icon="phosphor-planet-duotone" active="false">Public Profile</x-app.sidebar-link>
                {{-- @subscriber
                                <x-app.sidebar-link href="{{ '/profile/' . auth()->user()->username }}" icon="phosphor-credit-card">Manage Subscription</x-app.sidebar-link>
                @endsubscriber --}}


                <x-app.sidebar-link :hideUntilGroupHover="false" href="{{ route('settings.profile') }}" icon="phosphor-gear-duotone" active="false">Settings</x-app.sidebar-link>
                @notsubscriber
                <x-app.sidebar-link href="/settings/subscription" icon="phosphor-sparkle-duotone">Upgrade</x-app.sidebar-link>
                @endnotsubscriber
                @if(auth()->user()->isAdmin())
                <x-app.sidebar-link :hideUntilGroupHover="false" :ajax="false" href="/admin" icon="phosphor-crown-duotone" active="false">View Admin</x-app.sidebar-link>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button onclick="event.preventDefault(); this.closest('form').submit();" class="relative w-full flex cursor-pointer hover:text-zinc-700 dark:hover:text-zinc-100 select-none hover:bg-zinc-200 dark:hover:bg-zinc-700/60 items-center rounded-lg p-2 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                        <x-phosphor-sign-out-duotone class="flex-shrink-0 w-auto h-5 ml-1 mr-2" />
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