<!-- Loop Through Posts Here -->
@foreach($posts as $post)
    <article id="post-{{ $post->id }}" class="overflow-hidden relative col-span-2 bg-white rounded-md border border-black duration-150 ease-out transform cursor-pointer dark:bg-black sm:col-span-1 dark:border-white hover:scale-105" onClick="window.location='{{ $post->link() }}'">
        <meta property="name" content="{{ $post->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

        <img src="{{ $post->image() }}" class="w-full h-auto">
        <div class="p-5 pb-6">
            <h2 class="mb-2">
                <a href="{{ $post->link() }}" class="text-2xl font-bold tracking-tight leading-tight dark:text-gray-100">{{ $post->title }}</a>
            </h2>
            <p class="mb-2 text-sm font-medium tracking-widest text-gray-500 dark:text-gray-300">Written by <a href="#" class="hover:underline">{{ $post->user->name }}</a></p>
            <p class="text-gray-700 dark:text-gray-400">
                {{ substr(strip_tags($post->body), 0, 90) }}@if(strlen(strip_tags($post->body)) > 200){{ '...' }}@endif
            </p>
        </div>
    </article>
@endforeach
<!-- End Post Loop Here -->