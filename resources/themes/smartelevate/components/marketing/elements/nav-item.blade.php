@props([
    'dropdown' => false,
    'dropdown-ref' => '',
])

@if(!$dropdown ?? false)
    <a {{ $attributes->merge(['class' => 'flex cursor-pointer md:w-auto w-full items-center px-3 md:px-2 lg:px-3 py-3 md:py-2 text-xs font-bold leading-snug text-neutral-700 dark:hover:text-white dark:text-neutral-300 uppercase rounded-md md:rounded-full opacity-95 dark:hover:bg-white/15 hover:bg-black/5 hover:opacity-100']) }}>
        {{ $slot }}
    </a>
@else
    <button 
        data-ref="{{ $dropdownRef }}" 
        :class="{ 'opacity-95 text-neutral-900 dark:text-white dark:bg-white/15 bg-black/5' : navigationMenu=='{{ $dropdownRef }}' && navigationMenuOpen, 'hover:bg-black/5 text-neutral-700 dark:text-neutral-300 dark:hover:bg-white/15 dark:hover:text-neutral-100 hover:text-neutral-900 hover:opacity-100' : navigationMenu!= '{{ $dropdownRef }}' }" 
        @click="
            if(isMobile){
                navigationMenuOpen=true; 
                navigationMenuReposition($el); 
                navigationMenu='{{ $dropdownRef }}';
            }
        "
        @mouseenter="
            if(!isMobile){ openMegaMenu($el, '{{ $dropdownRef }}'); }
        " 
        @mouseleave="if(!isMobile){ navigationMenuLeave(); }" 
        class="flex items-center justify-between w-full px-3 py-3 text-xs font-bold leading-snug uppercase rounded-md md:rounded-full md:py-2 md:px-2 md:justify-start lg:w-auto lg:px-3">
        <span>{{ $slot }}</span>
        <x-phosphor-caret-up-bold class="block w-3 h-3 md:hidden" />
        <svg :class="{ '-rotate-180' : navigationMenuOpen==true && navigationMenu == '{{ $dropdownRef }}' }" class="relative md:block hidden top-[1px] ml-1 h-3 w-3 ease-out duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
    </button>
@endif