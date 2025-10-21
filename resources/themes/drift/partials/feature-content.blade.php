<div class="flex flex-col w-full lg:flex-row lg:grid-cols-3">
    <div class="flex-shrink-0 hidden p-6 mr-12 border border-gray-100 dark:border-gray-900 lg:block w-80 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white border border-gray-200 rounded-full dark:border-gray-800 dark:bg-gray-950">
            <x-dynamic-component :component="$icon" class="w-6 h-6 text-black dark:text-white" />
        </div>
        <h2 class="mb-2 text-lg font-bold dark:text-white">{{ $title }}</h2>
        <p class="mb-6 text-sm text-gray-600 dark:text-gray-500">{{ $description }}</p>
        <x-button class="w-full duration-300 ease-out transform-gpu hover:ring-2 hover:ring-offset-2 hover:ring-gray-800 dark:hover:ring-gray-200 dark:ring-offset-gray-900 group">Get Started</x-button>
    </div>
    <div class="grid grid-cols-1 gap-5 text-sm gap-x-16 md:col-span-2 md:grid-cols-2">
        @foreach($items as $item)
            <div class="flex items-center">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 mt-1">
                        <x-dynamic-component :component="$item['icon']" class="w-6 h-6 text-blue-600" />
                    </div>
                    <div>
                        <h3 class="mb-2 text-lg font-semibold dark:text-neutral-100">{{ $item['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-500">{{ $item['description'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>