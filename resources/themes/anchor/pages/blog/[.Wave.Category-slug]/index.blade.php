<?php
    use function Laravel\Folio\{name};
    name('blog.category');
?>

<x-layouts.marketing
    :seo="[
        'title' => $category->seo_title ?? $category->name . ' Articles',
        'description' => $category->meta_description ?? 'Our latest ' . $category->name . ' posts and articles.',
        'keywords' => $category->meta_keywords ?? $category->name,
        'image' => $category->image ? asset('storage/' . $category->image) : setting('site.logo', url('/wave/img/logo.png')),
        'type' => 'website'
    ]"
>

    @php
        $posts = $category->posts()->paginate(6);
    @endphp

    <x-container>
        <div class="relative pt-6">
            <x-marketing.elements.heading
                title="{{ $category->page_heading ?? $category->name . ' Articles' }}"
                description="{{ $category->page_description ?? 'Our latest ' . $category->name . ' posts below.' }}"
                align="left"
            />
            
            @include('theme::partials.blog.categories')

            <div class="grid gap-5 mx-auto mt-7 sm:grid-cols-2 lg:grid-cols-3">
                @include('theme::partials.blog.posts-loop', ['posts' => $posts])
            </div>
        </div>

        <div class="flex justify-center my-10">
            {{ $posts->links('theme::partials.pagination') }}
        </div>

    </x-container>
</x-layouts.marketing>
