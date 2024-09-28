@props([
    'level' => 'h1',
    'title' => 'No Heading Title Entered',
    'description' => 'Be sure to include the description attribute',
    'align' => 'center'
])


<div {{ $attributes->class([
        'relative w-full',
        'text-left' => $align == 'left',
        'text-right' => $align == 'right',
        'text-center' => $align != 'left' && $align != 'right'
    ]) }}>
    <{{ $level }} class="text-3xl sm:text-4xl text-left md:text-center font-medium tracking-tighter lg:text-5xl">{!! $title!!}</{{ $level }}>
    <p class="mt-4 text-sm sm:text-base font-medium text-left md:text-center md:text-balance text-zinc-500 @if($align == 'left'){{ 'ml-auto' }}@elseif($align == 'right'){{ 'mr-auto' }}@else{{ 'mx-auto max-w-2xl' }}@endif">{!! $description !!}</p>
</div>