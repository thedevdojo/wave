<a href="{{ $href }}" class="relative w-full flex items-center px-4 py-2.5 text-sm font-medium leading-5 @if($href == Request::url()){{ 'text-zinc-900 bg-zinc-100 border-zinc-200' }}@else{{ 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 hover:border-zinc-200 border-transparent' }}@endif transition duration-150 ease-in-out rounded-md group -mx-3 focus:outline-none focus:text-zinc-900 focus:bg-zinc-50">
    <x-dynamic-component :component="$icon" class="flex-shrink-0 mr-2 -ml-1 w-5 h-5" />
    <span class="hidden truncate md:inline-block">{{ $slot }}</span>
</a>