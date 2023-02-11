<header x-data="{ mobileMenuOpen: false }" class="relative z-30 @if(Request::is('/')){{ 'bg-white' }}@else{{ 'bg-gray-50' }}@endif">
    <div class="px-8 mx-auto xl:px-5 max-w-7xl">
        <div class="flex items-center justify-between h-24 border-b-2 border-gray-100 md:justify-start md:space-x-6">
            <div class="inline-flex">
            <!-- data-replace='{ "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }' -->
                <a href="{{ route('wave.home') }}" class="flex items-center justify-center space-x-3 transition-all duration-1000 ease-out transform text-wave-500">
                    @if(Voyager::image(theme('logo')))
                        <img class="h-9" src="{{ Voyager::image(theme('logo')) }}" alt="Company name">
                    @else
                        <svg class="w-9 h-9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 208 206"><defs/><defs><linearGradient id="a" x1="100%" x2="0%" y1="45.596%" y2="45.596%"><stop offset="0%" stop-color="#5D63FB"/><stop offset="100%" stop-color="#0769FF"/></linearGradient><linearGradient id="b" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stop-color="#39BEFF"/><stop offset="100%" stop-color="#0769FF"/></linearGradient><linearGradient id="c" x1="0%" x2="99.521%" y1="50%" y2="50%"><stop offset="0%" stop-color="#38BCFF"/><stop offset="99.931%" stop-color="#91D8FF"/></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="url(#a)" d="M185.302 38c14.734 18.317 22.742 41.087 22.698 64.545C208 159.68 161.43 206 103.986 206c-39.959-.01-76.38-22.79-93.702-58.605C-7.04 111.58-2.203 69.061 22.727 38a104.657 104.657 0 00-9.283 43.352c0 54.239 40.55 98.206 90.57 98.206 50.021 0 90.571-43.973 90.571-98.206A104.657 104.657 0 00185.302 38z"/><path fill="url(#b)" d="M105.11 0A84.144 84.144 0 01152 14.21C119.312-.651 80.806 8.94 58.7 37.45c-22.105 28.51-22.105 68.58 0 97.09 22.106 28.51 60.612 38.101 93.3 23.239-30.384 20.26-70.158 18.753-98.954-3.75-28.797-22.504-40.24-61.021-28.47-95.829C36.346 23.392 68.723.002 105.127.006L105.11 0z"/><path fill="url(#c)" d="M118.98 13c36.39-.004 66.531 28.98 68.875 66.234 2.343 37.253-23.915 69.971-60.006 74.766 29.604-8.654 48.403-38.434 43.99-69.685-4.413-31.25-30.678-54.333-61.459-54.014-30.78.32-56.584 23.944-60.38 55.28v-1.777C49.99 44.714 80.872 13.016 118.98 13z"/></g></svg>
                    @endif
                </a>
            </div>
            <div class="flex justify-end flex-grow -my-2 -mr-2 md:hidden">
                <button @click="mobileMenuOpen = true" type="button" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                </button>
            </div>

            <!-- This is the homepage nav when a user is not logged in -->
            @if(auth()->guest())
                @include('theme::menus.guest')
            @else <!-- Otherwise we want to show the menu for the logged in user -->
                @include('theme::menus.authenticated')
            @endif

        </div>
    </div>

    @if(auth()->guest())
        @include('theme::menus.guest-mobile')
    @else
        @include('theme::menus.authenticated-mobile')
    @endif
</header>
