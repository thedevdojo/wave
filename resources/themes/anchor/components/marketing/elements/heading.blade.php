@props([
    'level' => 'h1',
    'title' => 'No Heading Title Entered',
    'description' => 'Be sure to include the description attribute',
    'align' => 'center'
])


<div {{ $attributes->class([
        'relative w-full',
        'text-start' => $align == 'left',
        'text-end' => $align == 'right',
        'text-center' => $align != 'left' && $align != 'right'
    ]) }}>
    <{{ $level }} class="text-3xl sm:text-4xl text-start md:text-center font-medium tracking-tighter lg:text-5xl">{!! $title!!}</{{ $level }}>
    <p class="mt-4 text-sm sm:text-base font-medium text-start md:text-center md:text-balance text-zinc-500 @if($align == 'left'){{ 'ms-auto' }}@elseif($align == 'right'){{ 'me-auto' }}@else{{ 'mx-auto max-w-2xl' }}@endif">{!! $description !!}</p>
</div>