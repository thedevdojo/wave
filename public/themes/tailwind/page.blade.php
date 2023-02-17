@extends('theme::layouts.app')

@section('content')


    <div class="py-20 mx-auto max-w-7xl">
        <article id="page-{{ $page->id }}" class="max-w-4xl px-5 mx-auto prose prose-xl lg:prose-2xl lg:px-0">

            <meta property="name" content="{{ $page->title }}">
            <meta property="author" typeof="Person" content="admin">
            <meta property="dateModified" content="{{ Carbon\Carbon::parse($page->updated_at)->toIso8601String() }}">
            <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($page->created_at)->toIso8601String() }}">

            <h1>{{ $page->title }}</h1>

            @if(!is_null($page->image))
                <img width="1200" height="640" src="{{ $page->image() }}" alt="{{ $page->title }}" srcset="{{ $page->image() }}">
            @endif

            {!! $page->body !!}

        </article>
    </div>

@endsection
