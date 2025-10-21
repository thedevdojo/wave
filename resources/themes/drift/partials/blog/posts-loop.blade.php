<!-- Loop Through Posts Here -->
@foreach($posts as $post)
    <article class="relative flex flex-col justify-end px-8 pb-8 overflow-hidden bg-gray-900 isolate rounded-2xl pt-80 sm:pt-48 lg:pt-80">
        <meta property="name" content="{{ $post->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
        <meta property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

        <img src="{{ $post->image() }}" alt="{{ $post->title }}" class="absolute inset-0 object-cover w-full h-full -z-10">
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
        <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>

        <div class="flex flex-wrap items-center overflow-hidden text-sm leading-6 text-gray-300 gap-y-1">
            <time datetime="2020-03-16" class="mr-8">{{ $post->updated_at->format('M d, Y') }}</time>
            <div class="flex items-center -ml-4 gap-x-4">
                <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50"><circle cx="1" cy="1" r="1"></circle></svg>
                <a class="flex gap-x-2.5">
                    {{ $post->category->name }}
                </a>
            </div>
        </div>
        <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
            <a href="{{ $post->link() }}">
                <span class="absolute inset-0"></span>
                {{ $post->title }}
            </a>
        </h3>
    </article>
@endforeach
<!-- End Post Loop Here -->