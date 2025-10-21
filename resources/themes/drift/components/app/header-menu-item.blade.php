<div class="inline-flex pb-2.5 w-auto h-full">
    <a x-data wire:navigate @mouseover="setMarkerPosition($el.dataset.menu); hovering=true;" x-on:click="setActiveMarkerPosition($el.dataset.menu)" href="{{ $link }}" data-menu="{{ str_replace('/', '-', trim($link, '/')) }}" class="inline-flex items-center px-2.5 lg:px-3 py-1.5 text-sm font-medium rounded-md group" :class="{ ' text-black dark:text-white': menuItem == $el.dataset.menu, 'text-neutral-800 dark:text-neutral-200': menuItem != $el.dataset.menu }" x-on:click="menuItem = $el.dataset.menu" href="#">
        {{ $text }}
    </a>
</div>