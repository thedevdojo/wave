<a @click="navigationMenuClose()" {{ $attributes->merge(['class' => 'flex cursor-pointer items-start text-sm rounded-md group']) }}>
    <span class="flex items-center justify-center flex-shrink-0 w-10 h-10 mr-4 text-black bg-white border border-gray-200 rounded-md dark:border-gray-800 dark:text-gray-300 dark:bg-gray-800 group-hover:border-gray-900 group-hover:bg-black group-hover:dark:bg-gray-100 group-hover:dark:text-gray-900 group-hover:text-white">
        <x-dynamic-component :component="$icon" class="w-5 h-5" />
    </span>
    <span>
        <span class="flex items-center font-medium text-black dark:text-white">
            <span>{{ $title }}</span>
            <svg class="mt-0.5 -mr-1 ml-1.5 w-2.5 h-2.5 opacity-0 group-hover:opacity-100 stroke-black dark:stroke-white" style="stroke-width: 1.5" fill="none" viewBox="0 0 10 10" aria-hidden="true"><path class="transition opacity-0 group-hover:opacity-100" d="M0 5h7"></path><path class="transition group-hover:translate-x-[3px]" d="M1 1l4 4-4 4"></path></svg>
        </span>
        <span class="block text-xs font-light leading-4 opacity-50 dark:text-gray-200 group-hover:opacity-100">{{ $description }}</span>
    </span>
</a>