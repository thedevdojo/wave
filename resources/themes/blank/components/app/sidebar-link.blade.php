<a 
    {{ $attributes->except(['class']) }} 
    @class([
        'px-3 hover:bg-gray-100 font-medium cursor-pointer w-full block w-full py-2.5 text-sm',
        'bg-gray-100' => ($active ?? false) == true
    ])
>{{ $slot }}</a>