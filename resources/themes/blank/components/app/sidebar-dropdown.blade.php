<div class="relative w-full select-none">
    <div
        x-data="{ {{ $id }}: '{{ ($open ?? false) }}' }"
        @click="{{ $id }}=!{{ $id }}"
        :class="{ 'bg-gray-100': {{ $id }} == true }"
        class="@if($active ?? false){{ 'text-zinc-900 bg-zinc-100 text-sm font-medium' }}@else{{ 'hover:bg-gray-100' }}@endif flex w-full h-auto px-3 py-2.5 cursor-pointer text-sm hover:bg-zinc-100 justify-start items-center hover:text-zinc-900 overflow-hidden group-hover:autoflow-auto items"
    >
        <div class="flex relative items-center w-full h-auto">
            <span class="mr-0 font-medium">{{ $text }}</span>
            <span :class="{ 'rotate-180' : {{ $id }} == true }" class="mr-0.5 ml-auto w-4 h-4 duration-300 ease-out">
                <x-phosphor-caret-down class="w-full h-full" />
            </span>
        </div>

        <template x-teleport="#{{ $id }}">
            <div class="relative pt-1" x-show="{{ $id }}" x-collapse x-cloak>
                {{ $slot }}
            </div>
        </template>
    </div>

    <div id="{{ $id }}" class="relative ml-3.5 border-l border-gray-200"></div>

</div>
