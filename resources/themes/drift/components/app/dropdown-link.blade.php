@props([
    'href' => '',
    'highlight' => false
])

<a 
    href="{{ $href }}" 
    @class([
        'block px-4 py-2 text-sm leading-5 focus:outline-none',
        'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:bg-zinc-100 focus:text-zinc-900' => !$highlight,
        'text-indigo-100 bg-indigo-500 hover:bg-indigo-600 hover:text-white' => $highlight
    ])>
    {{ $slot }}
</a>