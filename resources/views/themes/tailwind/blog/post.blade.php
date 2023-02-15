@extends('theme::layouts.app')

@section('content')

    <div class="max-w-4xl px-5 mx-auto mt-10 lg:px-0">
        <a href="{{ route('wave.blog') }}" class="flex items-center mb-6 font-mono text-sm font-bold cursor-pointer text-wave-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            back to the blog
        </a>
    </div>
    <article id="post-{{ $post->id }}" class="max-w-4xl px-5 mx-auto prose prose-xl lg:prose-2xl lg:px-0">

        <meta property="name" content="{{ $post->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

        <div class="max-w-4xl mx-auto mt-6">

            <h1 class="flex flex-col leading-none">
                <span>{{ $post->title }}</span>
                <span class="mt-0 mt-10 text-base font-normal">Written on <time datetime="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">{{ Carbon\Carbon::parse($post->created_at)->toFormattedDateString() }}</time>. Posted in <a href="{{ route('wave.blog.category', $post->category->slug) }}" rel="category">{{ $post->category->name }}</a>.</span>
            </h1>


        </div>

        <div class="relative">
            <img class="w-full h-auto rounded-lg" src="{{ $post->image() }}" alt="{{ $post->title }}" srcset="{{ $post->image() }}">
        </div>

        <div class="max-w-4xl mx-auto">
            {!! $post->body !!}
        </div>

    </article>

@endsection
