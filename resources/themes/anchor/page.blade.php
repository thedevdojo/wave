<x-layouts.marketing>

    <x-elements.back-button
        class="max-w-3xl mx-auto mt-4 md:mt-8"
        text="Return Back Home"
        :href="route('home')"
    />
    
    <article id="post-{{ $page['id'] }}" class="max-w-3xl px-5 mx-auto mb-32 prose prose-lg lg:prose-xl lg:px-0">

        <meta property="name" content="{{ $page['title'] }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($page['updated_at'])->toIso8601String() }}">
        <meta property="datePublished" content="{{ Carbon\Carbon::parse($page['created_at'])->toIso8601String() }}">

        <div class="max-w-4xl mx-auto mt-6">

            <h1 class="flex flex-col leading-none">
                <span>{{ $page['title'] }}</span>
                {{-- <span class="mt-0 mt-10 text-base font-normal">Written on <time datetime="{{ Carbon\Carbon::parse($page->created_at)->toIso8601String() }}">{{ Carbon\Carbon::parse($page->created_at)->toFormattedDateString() }}</time>. Posted in <a href="{{ route('blog.category', $page->category->slug) }}" rel="category">{{ $page->category->name }}</a>.</span> --}}
            </h1>

        </div>

        @if($page['image'])
            <div class="relative">
                <img class="w-full h-auto rounded-lg" src="{{ url($page['image']) }}" alt="{{ url($page['image']) }}" srcset="{{ url($page['image']) }}">
            </div>
        @endif

        <div class="max-w-4xl mx-auto">
            {!! $page['body'] !!}
        </div>

    </article>

</x-layouts.marketing>
