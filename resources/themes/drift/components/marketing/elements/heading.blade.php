@props([
    'subheading' => '',
    'heading' => '',
    'description' => '',
    'align' => 'center',
    'level' => 'h2'
])

<div class="mb-8 text-left sm:mb-16 lg:mb-20 @if($align == 'center'){{ 'sm:text-center' }}@endif">
    @if($subheading ?? false)
        <p class="hidden mb-2 text-xs font-medium tracking-wider text-gray-400 uppercase sm:block sm:text-sm lg:mb-4">{{ $subheading ?? '' }}</p>
    @endif
    <{{ $level }} class="text-balance bg-gradient-to-br from-black from-30% to-black/60 bg-clip-text text-3xl sm:text-4xl lg:text-6xl pb-1 font-semibold leading-none tracking-tighter text-transparent dark:from-white dark:to-white/40">{{ $heading ?? '' }}</{{ $level }}>
    <p class="@if($align == 'center'){{ 'max-w-2xl md:mx-auto' }}@else{{ 'max-w-2xl ml-0' }}@endif mt-4 text-sm sm:text-base md:text-lg md:mt-5 lg:mt-6 xl:mt-7 dark:text-gray-300 opacity-70">{{ $description ?? '' }}</p>
</div>