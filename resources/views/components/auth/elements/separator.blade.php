<div {{ $attributes->merge(['class' => 'flex justify-center items-center w-full text-zinc-400 uppercase text-xs']) }}>
    <span class="w-full h-px bg-zinc-300"></span>
    <span class="px-2 w-auto">{{ $slot }}</span>
    <span class="w-full h-px bg-zinc-300"></span>
</div>