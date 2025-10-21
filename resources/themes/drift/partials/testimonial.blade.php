<div class="relative h-auto p-6 border border-gray-100 bg-gray-50 dark:border-gray-900 dark:bg-gray-900 rounded-xl">
    <div class="relative flex items-center pb-5">
        <img src="{{ $image ?? '' }}" class="w-12 h-12 mr-3 rounded-full">
        <div class="relative">
            <p class="my-1 font-medium leading-none text-gray-600 dark:text-gray-200">{{ $name ?? '' }}</p>
            <p class="text-sm font-medium text-gray-400 dark:text-gray-600">{{ $title ?? '' }}</p>
        </div>
    </div>
    <blockquote class="relative z-10 pb-3 leading-7 text-gray-400 dark:text-gray-500">"{{ $quote ?? '' }}"</blockquote>
    <svg class="absolute top-0 right-0 z-0 w-12 h-auto mt-6 text-gray-200 dark:text-gray-600 mr-7 opacity-30" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true"><path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"></path></svg>
</div>