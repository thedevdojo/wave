@props([
    'level' => 'h1',
    'title' => 'No Heading Title Entered',
    'description' => 'Be sure to include the description attribute'
])

<div class="relative w-full text-center">
    <{{ $level }} class="text-4xl font-bold tracking-tighter lg:text-5xl text-balance">{!! $title!!}</{{ $level }}>
    <p class="mx-auto mt-4 max-w-2xl text-base font-medium text-gray-500 text-balance">{!! $description !!}</p>
</div>