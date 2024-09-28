<div x-data="{ {{ $id }}: {{ $open ?? false }} }"
    :class="{ 'bg-zinc-200/70 dark:bg-zinc-800 dark:ring-zinc-800 ease-out duration-300 ring-2 ring-zinc-200/50 rounded-lg' : {{ $id }} == true }"
    class="relative w-full select-none">
    <div
        @click="{{ $id }}=!{{ $id }}"
        class="@if($active){{ 'text-zinc-900 bg-white border-zinc-200 shadow-sm font-medium dark:bg-zinc-700/60 dark:text-zinc-100' }}@endif ease-linear duration-50 transition-colors flex rounded-lg w-full h-auto px-2.5 py-2 cursor-pointer text-sm border justify-start items-center overflow-hidden group-hover:autoflow-auto items"
        :class="{ 'text-zinc-900 bg-white border-zinc-200 dark:border-zinc-700 shadow-sm font-medium dark:bg-zinc-700/60 dark:text-zinc-100' : {{ $id }} == true, 'hover:bg-zinc-100 dark:hover:bg-zinc-700/60 hover:text-zinc-900 border-transparent dark:hover:text-zinc-100' : ({{ $id }} == false && {{ !$active }}) }"
    >
        <div class="flex relative items-center w-full h-auto">
            <x-dynamic-component :component="$icon" class="flex-shrink-0 mr-2 w-5 h-5" />
            <span class="mr-0">{{ $text }}</span>
            <span :class="{ 'rotate-180' : {{ $id }} == true }" class="mr-0.5 ml-auto w-4 h-4 duration-300 ease-out">
                <x-phosphor-caret-down class="w-full h-full" />
            </span>
        </div>

        <template x-teleport="#{{ $id }}">
            <div class="relative p-1 space-y-1" x-show="{{ $id }}" x-collapse x-cloak>
                {{ $slot }}
            </div>
        </template>
    </div>

    <div id="{{ $id }}"></div>

</div>
