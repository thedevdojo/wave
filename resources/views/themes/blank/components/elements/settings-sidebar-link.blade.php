<a href="{{ $href }}" class="@if($href == RalphJSmit\Livewire\Urls\Facades\Url::current()){{ 'bg-zinc-100 text-zinc-900 font-semibold' }}@else{{ 'hover:bg-zinc-100 text-zinc-600 font-medium' }}@endif flex justify-start items-center px-4 py-2 text-sm whitespace-nowrap group transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 relative">
    <x-dynamic-component :component="$icon" class="flex-shrink-0 mr-1 -ml-1.5 w-4 h-4" />
    <span class="hidden truncate md:inline-block">{{ $slot }}</span>
</a>