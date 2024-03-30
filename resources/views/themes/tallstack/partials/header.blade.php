<header x-data="{ mobileMenuOpen: false }" class="relative z-30 @if(Request::is('/')){{ 'bg-white' }}@else{{ 'bg-gray-50' }}@endif">
    <div class="px-8 mx-auto max-w-7xl xl:px-5">
        <div class="flex justify-between items-center h-24 border-b-2 border-gray-100 md:justify-start md:space-x-6">
            <div class="inline-flex">
            <!-- data-replace='{ "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }' -->
                <a href="{{ route('wave.home') }}" class="flex justify-center items-center space-x-3 transition-all duration-1000 ease-out transform text-wave-500">
                    @if(theme('logo'))
                        <img class="h-9" src="{{ Voyager::image(theme('logo')) }}" alt="Company name">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-auto h-5" viewBox="0 0 738 129" fill="none"><path fill="#00CEFF" d="M198.883 128.814h-39.31c5.774-34.761-4.775-71.74-31.537-98.613l27.428-27.539c34.425 34.425 48.86 81.29 43.419 126.152Z"/><path fill="#009AFF" d="M158.24 110.265a114.936 114.936 0 0 1-1.555 18.549h-39.976a71.03 71.03 0 0 0-18.323-69.073c-13.658-13.55-31.536-20.767-51.08-20.875L47.083 0c29.872.108 57.744 11.213 78.843 32.31 20.877 20.767 32.314 48.529 32.314 77.955Z"/><path fill="#006EFF" d="M113.267 128.814H70.181c9.439-11.332 8.772-28.32-1.776-38.98-11.327-11.218-29.65-11.327-40.976 0L0 62.295c26.429-26.428 69.403-26.428 95.944 0 17.989 17.99 23.764 43.643 17.323 66.519Z"/><path fill="#000" d="M232.581 24.014h23.8l11.2 36.4 10.6 36.8 25.4-73.2h19l25.2 73.6 21.8-73.6h23.8l-32.8 98.8h-23.8l-9.4-28.2-14.2-39.6-14.2 39.6-9.4 28.2h-24l-33-98.8ZM448.295 125.014c-9.6 0-18.134-2.133-25.6-6.4-7.334-4.4-13.067-10.533-17.2-18.4-4.134-7.867-6.2-16.8-6.2-26.8s2.066-18.933 6.2-26.8c4.133-7.867 9.866-13.933 17.2-18.2 7.466-4.4 16-6.6 25.6-6.6 7.6 0 14.533 1.4 20.8 4.2 6.266 2.667 11.333 6.4 15.2 11.2l.2-13.2h20v98.8h-20l-.2-13.2c-3.867 4.8-8.934 8.6-15.2 11.4-6.267 2.667-13.2 4-20.8 4Zm-26.6-51.6c0 9.2 2.8 16.733 8.4 22.6 5.6 5.733 12.866 8.6 21.8 8.6 8.933 0 16.2-2.867 21.8-8.6 5.6-5.867 8.4-13.4 8.4-22.6 0-9.2-2.8-16.667-8.4-22.4-5.6-5.867-12.867-8.8-21.8-8.8-8.934 0-16.2 2.933-21.8 8.8-5.6 5.733-8.4 13.2-8.4 22.4ZM521.271 24.014h24.4l13.8 33.8 15.8 41.8 16-41.8 13.8-33.8h24l-41.8 98.8h-24l-42-98.8ZM689.027 125.614c-10.8 0-20.266-2.133-28.399-6.4-8-4.4-14.201-10.533-18.601-18.4-4.4-7.867-6.6-17-6.6-27.4 0-10.133 2.134-19.067 6.401-26.8 4.266-7.733 10.266-13.733 18-18 7.733-4.4 16.666-6.6 26.8-6.6 16 0 28.533 4.6 37.6 13.8 9.066 9.2 13.6 21.933 13.6 38.2 0 1.733-.134 4.333-.401 7.8h-79c.934 7.2 4.201 12.933 9.801 17.2 5.6 4.267 12.733 6.4 21.4 6.4 5.6 0 11-.933 16.2-2.8 5.2-1.867 9.266-4.333 12.199-7.4l13.601 13.2c-4.934 5.333-11.2 9.533-18.8 12.6-7.467 3.067-15.401 4.6-23.801 4.6Zm27-61.2c-.8-7.067-3.8-12.667-9-16.8-5.066-4.133-11.666-6.2-19.799-6.2-7.734 0-14.067 2-19 6-4.934 4-8.134 9.667-9.6 17h57.399Z"/></svg>
                    @endif
                </a>
            </div>
            <div class="flex flex-grow justify-end -my-2 -mr-2 md:hidden">
                <button @click="mobileMenuOpen = true" type="button" class="inline-flex justify-center items-center p-2 text-gray-400 rounded-md transition duration-150 ease-in-out hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
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
