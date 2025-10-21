<x-layouts.marketing>

    @php
        $page = json_decode(json_encode($page), FALSE);
    @endphp
    <x-marketing.elements.heading-full
        heading="{{ $page->title }}"
        description="{{ $page->excerpt }}"
        align="left"
        maxWidth="max-w-4xl"
    />

    <article id="post-{{ $page->id }}" class="max-w-4xl px-6 pb-20 mx-auto prose md:px-10 prose-md dark:prose-invert lg:prose-lg lg:px-0">

        <meta property="name" content="{{ $page->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($page->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($page->created_at)->toIso8601String() }}">

        @if($page->image)
            <div class="relative">
                <img class="w-full h-auto rounded-lg !mt-0 !pt-0" src="{{ $page->image() }}" alt="{{ $page->title }}" srcset="{{ $page->image() }}">
            </div>
        @endif

        <div class="max-w-4xl mx-auto">
            {!! $page->body !!}
        </div>

    </article>

</x-layouts.marketing>
