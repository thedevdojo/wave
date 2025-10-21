<div class="relative z-30">
    <a href="{{ $href ?? '' }}" wire:navigate class="inline-flex group hover:bg-white dark:hover:bg-gray-800 bg-gray-50 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-200 items-center mb-6 text-xs font-bold text-black cursor-pointer px-3 py-1.5 border border-gray-200 rounded-lg">
        <svg class="w-3.5 h-3.5 mr-1 duration-300 ease-out translate-x-px group-hover:-translate-x-px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        {{ $text ?? '' }}
    </a>
</div>