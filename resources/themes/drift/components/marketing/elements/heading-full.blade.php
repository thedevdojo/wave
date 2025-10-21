@props([
    'subheading' => '',
    'heading' => '',
    'description' => '',
    'align' => 'center',
    'level' => 'h2',
    'maxWidth' => ''
])

<div class="text-left border-t border-gray-100 dark:border-neutral-900 relative @if($align == 'center'){{ 'sm:text-center' }}@endif">
    <div class="relative z-20 md:py-10 py-8 lg:py-12 mx-auto @if($maxWidth){{ $maxWidth . ' lg:px-0 px-6 md:px-10' }}@else{{ 'max-w-7xl px-6 md:px-10' }}@endif">
        <x-marketing.elements.colorful-mesh class="absolute bottom-0 z-10 left-1/2 w-auto -translate-y-full h-full scale-[3.5] opacity-10" />
        <div class="relative z-20">
        @if($subheading ?? false)
            <p class="hidden mb-2 text-xs font-medium tracking-wider text-gray-400 uppercase sm:block sm:text-sm lg:mb-4">{{ $subheading ?? '' }}</p>
        @endif
        <{{ $level }} class="text-balance bg-gradient-to-br pb-1 from-black from-30% to-black/60 bg-clip-text text-3xl sm:text-4xl lg:text-6xl font-semibold leading-none tracking-tighter text-transparent dark:from-white dark:to-white/40">{{ $heading ?? '' }}</{{ $level }}>
            <p class="@if($align == 'center'){{ 'max-w-2xl md:mx-auto' }}@else{{ 'max-w-full ml-0' }}@endif mt-2 text-sm sm:text-base dark:text-gray-300 opacity-70">{{ $description ?? '' }}</p>
        </div>
    </div>
    
</div>