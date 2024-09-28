<!-- Loop Through Posts Here -->
@foreach($posts as $post)
    <article id="post-{{ $post->id }}" class="overflow-hidden relative col-span-2 p-4 bg-white rounded-2xl border cursor-pointer border-zinc-200 border-zinc-100 dark:bg-black sm:col-span-1" onClick="window.location='{{ $post->link() }}'">
        <meta property="name" content="{{ $post->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

        <img src="{{ $post->image() }}" class="w-full h-auto rounded-lg">
        <div class="px-1 py-1">
            <div class="flex gap-x-4 items-center my-3 text-xs">
                <time datetime="2020-03-16" class="text-zinc-500">{{ $post->updated_at->format('M d, Y') }}</time>
                <a href="/blog/{{ $post->category->slug }}" class="relative z-10 px-3 py-1.5 font-medium text-zinc-600 bg-zinc-50 rounded-full hover:bg-zinc-100">{{ $post->category->name }}</a>
              </div>
            <h2 class="text-lg font-semibold leading-6 text-zinc-900 group-hover:text-zinc-600">
                <a href="{{ $post->link() }}">
                    <span class="absolute inset-0"></span>
                    {{ $post->title }}
                </a>
            </h2>
            <p class="mt-5 text-sm leading-6 text-zinc-600 line-clamp-3">{{ substr(strip_tags($post->body), 0, 110) }}@if(strlen(strip_tags($post->body)) > 200){{ '...' }}@endif</p>
            
        </div>
    </article>
@endforeach
<!-- End Post Loop Here -->