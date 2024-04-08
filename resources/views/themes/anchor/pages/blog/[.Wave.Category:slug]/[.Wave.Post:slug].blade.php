<?php
    use function Laravel\Folio\{name};
    name('blog.post');
?>

<x-layouts.marketing>

    <div class="px-5 mx-auto mt-10 max-w-4xl lg:px-0">
        <a href="{{ route('blog') }}" class="inline-flex items-center px-3 py-2 mb-6 font-mono text-xs font-bold rounded-full cursor-pointer text-zinc-900 bg-zinc-200 group">
            <svg class="mr-2 w-4 h-4 duration-200 ease-out group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            back to the blog
        </a>
    </div>
    <article id="post-{{ $post->id }}" class="px-5 pb-20 mx-auto max-w-4xl prose prose-xl lg:prose-2xl lg:px-0">

        <meta property="name" content="{{ $post->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

        <div class="mx-auto mt-6 max-w-4xl">

            <h1 class="flex flex-col leading-none">
                <span>{{ $post->title }}</span>
                {{-- <span class="mt-0 mt-10 text-base font-normal">Written on <time datetime="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">{{ Carbon\Carbon::parse($post->created_at)->toFormattedDateString() }}</time>. Posted in <a href="{{ route('blog.category', $post->category->slug) }}" rel="category">{{ $post->category->name }}</a>.</span> --}}
            </h1>


        </div>

        <div class="relative">
            <img class="w-full h-auto rounded-lg" src="{{ $post->image() }}" alt="{{ $post->title }}" srcset="{{ $post->image() }}">
        </div>

        <div class="mx-auto max-w-4xl">
            {!! $post->body !!}
        </div>

    </article>

</x-layouts.marketing>
