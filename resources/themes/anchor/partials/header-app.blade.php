<header x-data="{ mobileMenuOpen: false }" class="absolute z-30 w-full">
    <x-container>
        <div class="flex relative z-30 justify-between items-center h-24 md:space-x-8">
            <div class="inline-flex">
            <!-- data-replace='{ "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }' -->
                <a href="{{ route('home') }}" class="flex justify-center items-center space-x-3 text-blue-500 brightness-0 transition-all duration-300 ease-out transform hover:brightness-100 grayscale-100">
                   <x-logo class="w-auto h-7"></x-logo>
                </a>
            </div>
            <div class="flex flex-grow justify-end -my-2 -mr-2 md:hidden">
                <button @click="mobileMenuOpen = true" type="button" class="inline-flex justify-center items-center p-2 rounded-full transition duration-150 ease-in-out text-zinc-400 hover:text-zinc-500 hover:bg-zinc-100 focus:outline-none focus:bg-zinc-100 focus:text-zinc-500">
                    <svg class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                </button>
            </div>

            @include('theme::partials.menus.app')

        </div>
    </x-container>
    
    @include('theme::partials.menus.app-mobile')
</header>
