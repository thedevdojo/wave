<?php
    use function Laravel\Folio\{name};
    name('page');
?>

<x-layouts.marketing>

    <article id="page-{{ $page->id }}" class="px-5 pb-20 mx-auto max-w-4xl prose prose-xl lg:prose-2xl lg:px-0">

        <meta property="name" content="{{ $page->title }}">
        <meta property="author" typeof="Person" content="{{ $page->author->name }}">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($page->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($page->created_at)->toIso8601String() }}">

        <div class="mx-auto mt-6 max-w-4xl">
            <h1 class="flex flex-col leading-none">
                <span>{{ $page->title }}</span>
            </h1>
        </div>

        @if($page->image)
        <div class="relative">
            <img class="w-full h-auto rounded-lg" src="{{ $page->image() }}" alt="{{ $page->title }}" srcset="{{ $page->image() }}">
        </div>
        @endif

        <div class="mx-auto max-w-4xl">
            {!! $page->body !!}
        </div>

    </article>

</x-layouts.marketing>
