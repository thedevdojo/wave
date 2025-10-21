<?php
    use function Laravel\Folio\{name};
    name('blog.category');
?>

<x-layouts.marketing>

    @php
        $posts = $category->posts()->paginate(6);
    @endphp

    <x-marketing.elements.heading-full
        heading="{{ $category->name }} Articles"
        description="Our latest {{ $category->name }} posts below."
        level="h1"
        align="left"
    />
    
    @include('theme::partials.blog.categories')


    <div class="w-full px-5 mx-auto max-w-7xl md:px-8">
        <div class="relative pt-6">
            <div class="grid gap-5 mx-auto mt-7 sm:grid-cols-2 lg:grid-cols-3">
                @include('theme::partials.blog.posts-loop', ['posts' => $posts])
            </div>
        </div>
        <div class="flex justify-center my-10">
            {{ $posts->links('theme::partials.pagination') }}
        </div>
    </div>
</x-layouts.marketing>
