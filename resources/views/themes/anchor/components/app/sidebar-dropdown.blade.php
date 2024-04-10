<div class="relative w-full select-none">
    <div
        x-data="{ {{ $id }}: {{ $open ?? false }} }"
        @click="{{ $id }}=!{{ $id }}"
        class="@if($active){{ 'text-zinc-900 bg-zinc-100 font-medium dark:bg-zinc-700/60 dark:text-zinc-100' }}@else{{ 'hover:theme-text' }}@endif ease-linear duration-50 transition-colors flex rounded-md w-full h-auto px-2.5 py-2 cursor-pointer text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 overflow-hidden group-hover:autoflow-auto items"
    >
        <div class="flex relative items-center w-full h-auto">
            <x-dynamic-component :component="$icon" class="flex-shrink-0 mr-2 w-5 h-5" />
            <span class="mr-0">{{ $text }}</span>
            <span :class="{ 'rotate-180' : {{ $id }} == true }" class="mr-0.5 ml-auto w-4 h-4 duration-300 ease-out">
                <x-phosphor-caret-down class="w-full h-full" />
            </span>
        </div>

        <template x-teleport="#{{ $id }}">
            <div class="relative pt-1 pb-3 space-y-1" x-show="{{ $id }}" x-collapse x-cloak>
                {{ $slot }}
            </div>
        </template>
    </div>

    <div id="{{ $id }}" class="pl-2"></div>

</div>
