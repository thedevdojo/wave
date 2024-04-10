@props([
    'description' => ''
])

<div class="mx-auto my-5 max-w-xs">
    <img src="/wave/img/empty-state-dark.png" class="hidden mx-auto my-2 w-full dark:block" />
    <img src="/wave/img/empty-state.png" class="block mx-auto my-2 w-full opacity-50 dark:hidden" />
    <p class="font-medium text-center text-black opacity-30 dark:text-white">{{ $description ?? '' }}</p>
</div>