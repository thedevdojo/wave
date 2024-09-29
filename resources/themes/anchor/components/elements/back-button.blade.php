<div {{ $attributes->twMerge('lg:px-5 mx-auto w-full lg:px-0') }}>
    <a href="{{ $href ?? '' }}" wire:navigate class="inline-flex items-center px-2.5 py-1.5 mb-3 lg:mb-1 md:mb-6 text-xs font-semibold rounded-full border cursor-pointer text-zinc-900 bg-zinc-100 border-zinc-200 group">
        <svg class="relative mr-2 -ml-0.5 w-3.5 h-3.5 duration-200 ease-out translate-x-1 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        {{ $text ?? '' }}
    </a>
</div>