<li>
    <button
        x-on:click="menuItemClicked('{{ $slug }}');" 
        {{-- x-on:click="display='{{ strtolower($text) }}'; $wire.dispatch('wave_admin_content', '{{ str_replace(" ", "-", strtolower($text)) }}'); adminContentLoading = true; secondaryMenu=true;" --}}
        :class="{ 'text-black': sidebar_active == '{{ $slug }}', 'text-gray-500 hover:text-black' : sidebar_active != '{{ $slug }}' }"
        class="flex justify-start items-center w-full duration-300 ease-out group">
        <span class="relative font-semibold jakarta">
            <span>{{ $text }}</span>
            <span :class="{ 'w-full': sidebar_active == '{{ $slug }}', 'w-0 group-hover:w-full' : sidebar_active != '{{ $slug }}' }" class="block absolute bottom-0 left-0 h-0.5 bg-black duration-300 ease-out group-hover:ease-in-out translate-y-[3px]"></span>
        </span>
    </button>
</li>