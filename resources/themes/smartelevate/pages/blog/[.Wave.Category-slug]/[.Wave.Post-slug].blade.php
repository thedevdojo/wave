<?php
    use function Laravel\Folio\{name};
    name('blog.post');
?>

<x-layouts.marketing>


    <x-marketing.elements.heading-full
        heading="{{ $post->title }}"
        description="Written or modified on {{ $post->updated_at->format('M d, Y') }}"
        align="left"
        maxWidth="max-w-4xl"
    />

    <div class="relative z-30 max-w-4xl px-5 mx-auto -translate-y-4 lg:px-0">
        <x-elements.back-button
            class="max-w-4xl mx-auto mt-8"
            text="back to the blog"
            :href="route('blog')"
        />
    </div>
    
    <article id="post-{{ $post->id }}" class="max-w-4xl px-6 pb-20 mx-auto prose md:px-10 prose-md dark:prose-invert lg:prose-lg lg:px-0">

        <meta property="name" content="{{ $post->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">


        <div class="relative">
            <img class="w-full h-auto rounded-lg !mt-0 !pt-0" src="{{ $post->image() }}" alt="{{ $post->title }}" srcset="{{ $post->image() }}">
        </div>

        <div class="max-w-4xl mx-auto">
            {!! $post->body !!}
        </div>

    </article>

</x-layouts.marketing>
