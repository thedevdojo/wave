<header x-data="{ mobileMenuOpen: false }" class="relative z-30 @if(Request::is('/')){{ 'bg-white' }}@else{{ 'bg-gray-50' }}@endif">
    <div class="px-8 mx-auto xl:px-5 max-w-7xl">
        <div class="flex items-center justify-between h-24 border-b-2 border-gray-100 md:justify-start md:space-x-6">
            <div class="inline-flex">
            <!-- data-replace='{ "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }' -->
                <a href="{{ route('wave.home') }}" class="flex items-center justify-center space-x-3 transition-all duration-1000 ease-out transform text-wave-500">
                    @if(Voyager::image(theme('logo')))
                        <img class="h-9" src="{{ Voyager::image(theme('logo')) }}" alt="Company name">
                    @else
                        <svg class="w-9 h-9" viewBox="0 0 73 109" xmlns="http://www.w3.org/2000/svg"><path d="M0 21.174v12.11l5.186-3.011 5.242-3.044 5.186-3.011 5.24-3.044 5.188-3.012 5.242-3.044v36.334l-5.242 3.044-5.188 3.011-5.24 3.044-5.186 3.011v-12.11l5.186-3.012 5.24-3.044 5.188-3.012 2.563-1.494V28.778l-2.563 1.494-5.188 3.011-5.24 3.044-5.186 3.013-5.236 3.049L.006 45.4v36.333l5.186-3.012 5.242-3.044 5.186-3.011 5.24-3.044 5.188-3.012 5.242-3.044v36.334l-5.242 3.042 5.242 3.044L36.476 109l5.23-3.046 5.187-3.013-5.188-3.01v-12.11l5.188 3.012 5.246 3.045 5.186 3.011 5.24-3.044 5.188-3.011 5.24-3.044V69.655l-5.24-3.041-5.187-3.012-5.24-3.044-5.187-3.012-5.235-3.045-2.51-1.457v12.11l7.752 4.501 5.186 3.012 5.24 3.044v6.023l-5.24 3.043-5.186-3.011-5.242-3.044-5.199-3.011V39.377l5.188 3.012 5.241 3.044 5.186 3.012 5.241 3.043 5.186 3.013 5.242 3.042v-12.11l-5.24-3.044-5.188-3.012-5.24-3.044-5.187-3.011-5.23-3.044-5.199-3.012v-12.11l5.199 3.011 5.242 3.044 5.186 3.012 5.24 3.043 5.187 3.012L73 33.322v-12.11l-5.24-3.045-5.188-3.011-5.24-3.044-5.193-3.014-5.235-3.041-5.199-3.013L36.465 0l-5.186 3.013-5.242 3.044-5.187 3.011-5.241 3.044-5.186 3.011-5.23 3.044L0 21.174zM29 77v11.705l-2.628 1.443-5.317 2.91L15.683 96l-5.316-2.91L5 90.147l5.373-2.941 5.316-2.91 5.372-2.942 5.318-2.91L29 77z" fill="currentColor" fill-rule="evenodd"/></svg>
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
