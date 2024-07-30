<?php
    use function Laravel\Folio\{name};
    name('blog.category');
?>

<x-layouts.marketing>

    @php
        $posts = $category->posts()->paginate(6);
    @endphp

    <x-container>
        <div class="relative pt-6">
            <x-marketing.elements.heading
                title="{{ $category->name }} Articles"
                description="Our latest {{ $category->name }} posts below."
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
