@props([
    'title' => '',
    'description' => '',
    'border' => true
])

<div class="px-1 py-6 bg-white border-b border-gray-200 md:py-10 md:px-0 dark:bg-black dark:border-gray-800">
    <x-app.container>
        <h3 class="mb-2 text-2xl font-medium tracking-tight md:text-3xl dark:text-zinc-100">{{ $title ?? '' }}</h3>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $description ?? '' }}</p>
    </x-app.container>
</div>