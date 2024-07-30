<?php
    use function Laravel\Folio\{name};
    name('blog.post');
?>

<x-layouts.marketing>

    <x-elements.back-button
        class="mx-auto mt-8 max-w-4xl"
        text="back to the blog"
        :href="route('blog')"
    />
    
    <article id="post-{{ $post->id }}" class="px-5 pb-20 mx-auto max-w-4xl prose prose-md lg:prose-lg lg:px-0">

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
