@props([
    'level' => 'h1',
    'title' => 'No Heading Title Entered',
    'description' => 'Be sure to include the description attribute',
    'align' => 'center'
])

<div class="relative w-full @if($align == 'left'){{ 'text-left' }}@elseif($align == 'right'){{ 'text-right' }}@else{{ 'text-center' }}@endif">
    <{{ $level }} class="text-4xl font-medium tracking-tighter lg:text-5xl text-balance">{!! $title!!}</{{ $level }}>
    <p class="mt-4 text-base font-medium text-gray-500 text-balance @if($align == 'left'){{ 'ml-auto' }}@elseif($align == 'right'){{ 'mr-auto' }}@else{{ 'mx-auto max-w-2xl' }}@endif">{!! $description !!}</p>
</div>