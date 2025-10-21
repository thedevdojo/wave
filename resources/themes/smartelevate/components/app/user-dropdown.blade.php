<x-dropdown id="user-dropdown" placement="top-end" width="xs" class="z-50">
    <x-slot name="trigger">
        <button class="border border-gray-100 mr-2 md:mr-2 rounded-md md:hidden block dark:border-gray-800">
            <x-avatar :src="auth()->user()->avatar()" :alt="auth()->user()->name" size="xs" :circular="false" />
        </button>
        <button class="border border-gray-100 mr-2 md:mr-2 rounded-full md:block hidden dark:border-gray-800">
            <x-avatar :src="auth()->user()->avatar()" :alt="auth()->user()->name" size="xs" />
        </button>
    </x-slot>

    <div class="relative">
        <div class="flex items-center p-5 space-x-2 p-sm">
            <div class="flex-shrink-0 border rounded-full border-neutral-200">
                <x-avatar :src="auth()->user()->avatar()" :alt="auth()->user()->name" size="xs" />
            </div>
            <div class="flex flex-col items-start justify-center w-full overflow-hidden text-sm text-neutral-600 dark:text-gray-300">
                <p class="w-full text-xs font-semibold truncate overflow-ellipsis">{{ auth()->user()->name }}</p>
                <p class="w-full text-xs font-medium truncate overflow-ellipsis">{{ '@' . auth()->user()->username }}</p>
            </div>
        </div>
    </div>
    <div class="relative px-1 py-1.5">
        <x-app.light-dark-toggle></x-app.light-dark-toggle>
    </div>
    {{-- TODO: update the links in this user menu to be named routes --}}
    <x-dropdown.list class="!border-0 !pt-0 !rounded-3xl">
        <hr class="-mx-1 mb-1.5 border-gray-100 dark:border-gray-800">

        <x-dropdown.list.item href="{{ route('notifications') }}" tag="a" icon="phosphor-bell-duotone" active="false">Notifications</x-dropdown.list.item>
        <x-dropdown.list.item href="{{ '/profile/' . auth()->user()->username }}" tag="a" icon="phosphor-planet-duotone" active="false">Public Profile</x-dropdown.list.item>
        {{-- @subscriber
            <x-app.sidebar-link href="{{ '/profile/' . auth()->user()->username }}" icon="phosphor-credit-card">Manage Subscription</x-app.sidebar-link>
        @endsubscriber --}}
        
        
        <x-dropdown.list.item href="{{ route('settings.profile') }}" tag="a" icon="phosphor-gear-duotone" active="false">Settings</x-dropdown.list.item>

        
        <hr class="-mx-1 my-1.5 border-gray-100 dark:border-gray-800">

        @notsubscriber
            <x-dropdown.list.item href="/settings/subscription"  tag="a" icon="phosphor-sparkle-duotone">Upgrade</x-dropdown.list.item>
        @endnotsubscriber
        @if(auth()->user()->isAdmin())
            <x-dropdown.list.item href="/admin" tag="a" icon="phosphor-crown-duotone" active="false">View Admin</x-app.sidebar-link>
        @endif
        <hr class="-mx-1 my-1.5 border-gray-100 dark:border-gray-800">
        <x-dropdown.list.item icon="phosphor-sign-out-duotone" tag="a" href="/logout" class="!rounded-b-xl">Log Out</x-dropdown.list.item>
    </x-dropdown.list>

</x-dropdown>