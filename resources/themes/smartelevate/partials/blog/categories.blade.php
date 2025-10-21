<div class="sticky z-20 inline-flex self-start w-full text-xs font-medium text-gray-600 border-b border-gray-500/5 top-16 scroll-pt-16 bg-white/90 dark:bg-neutral-950/90 backdrop-blur-xl">
    <div class="flex items-center justify-start w-full px-5 mx-auto text-left max-w-7xl md:px-8">
        <ul class="inline-flex px-1 py-3 dark:text-neutral-400">
            <li class="mr-4 font-bold uppercase text-zinc-800 sm:block hidden dark:text-neutral-200">Categories:</li>
            <li class="@if(!isset($category)){{ 'text-zinc-800 dark:text-neutral-200' }}@endif"><a href="{{ route('blog') }}">View All</a></li>
            <li class="mx-2">&middot;</li>
            @foreach(\Wave\Category::all() as $cat)
            <li class="@if(isset($category) && isset($category->slug) && ($category->slug == $cat->slug)){{ 'text-zinc-800 dark:text-neutral-200' }}@endif"><a href="{{ route('blog.category', ['category' => $cat]) }}">{{ $cat->name }}</a></li>
            @if(!$loop->last)
            <li class="mx-2">&middot;</li>
            @endif
            @endforeach
        </ul>
    </div>
</div>