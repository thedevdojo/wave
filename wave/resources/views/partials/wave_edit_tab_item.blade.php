<li>
    <button
        {{-- x-on:click="menuItemClicked('{{ $slug }}');"  --}}
        {{-- x-on:click="display='{{ strtolower($text) }}'; $wire.dispatch('wave_admin_content', '{{ str_replace(" ", "-", strtolower($text)) }}'); adminContentLoading = true;" --}}
        x-on:click="collapsed = true; window.location.href='{{ $link ?? '' }}'"
        x-on:keydown.window="console.log(event.key); if(!collapsed && event.key === '{{ $key ?? '' }}') { collapsed=true; window.location.href='{{ $link ?? '' }}' }"
        class="flex justify-start items-center w-full text-gray-300 duration-300 ease-out hover:text-white group">
        @if(isset($key))
                <span class="flex justify-center items-center mr-1.5 ml-1 w-6 h-6 font-mono text-xs leading-normal text-white bg-gray-800 rounded-md border border-gray-700 aspect-square">{{ $key }}</span>
            @endif
        <span class="flex relative items-center font-semibold jakarta">
            {{-- <x-dynamic-component :component="$icon ?? 'phosphor-cube'" class="mr-1.5 w-5 h-5" /> --}}
            <span>{{ $text }}</span>
            <span class="block w-0 group-hover:w-full absolute bottom-0 left-0 h-0.5 bg-white duration-300 ease-out group-hover:ease-in-out translate-y-[3px]"></span>
        </span>
        
    </button>
</li>