<?php
    use function Laravel\Folio\{name};
    name('blog');

    $posts = \Wave\Post::orderBy('created_at', 'DESC')->paginate(6);
    $categories = \Wave\Category::all();
?>

<x-layouts.marketing
    :seo="[
        'title' => 'Blog',
        'description' => 'Our Blog',
    ]"
>

    <div class="relative z-30 pb-10 bg-white dark:bg-black">

        <x-marketing.elements.heading-full
            heading="Articles and Posts"
            description="Check out some of our latest blog posts below and articles."
            align="left"
            level="h1"
        />

        @include('theme::partials.blog.categories')

        <div class="px-5 mx-auto max-w-7xl md:px-8">
            <div class="relative">
                <div class="grid max-w-2xl grid-cols-1 gap-8 mx-auto mt-10 auto-rows-fr lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    @include('theme::partials.blog.posts-loop', ['posts' => $posts])
                </div>
            </div>

            <div class="flex justify-center my-10">
                {{ $posts->links('theme::partials.pagination') }}
            </div>
        </div>

    </div>
</x-layouts.marketing>
