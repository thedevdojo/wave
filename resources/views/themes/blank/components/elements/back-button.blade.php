<div {{ $attributes->twMerge('px-5 mx-auto w-full lg:px-0 mb-6') }}>
    <a href="{{ $href ?? '' }}" wire:navigate class="inline-flex items-center px-4 py-2.5 text-[0.7rem] font-semibold cursor-pointer text-gray-900 bg-gray-200 group">
        <svg class="mr-2 -ml-0.5 w-3.5 h-3.5 duration-200 ease-out translate-x-1 group-hover:translate-x-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        {{ $text ?? '' }}
    </a>
</div>