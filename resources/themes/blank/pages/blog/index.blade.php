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
    <x-container>
        <div class="relative pt-6">
            <x-marketing.heading
                title="From The Blog"
                description="Check out some of our latest blog posts below."
                align="left"
            />
            
            <ul class="inline-flex self-start px-3 py-2 mt-7 w-auto text-xs font-medium border border-black">
                <li class="mr-4 font-bold uppercase text-zinc-800">Categories:</li>
                <li class="@if(!isset($category)){{ 'text-zinc-800' }}@endif"><a href="{{ route('blog') }}">View All</a></li>
                <li class="mx-2">&middot;</li>
                @foreach(\Wave\Category::all() as $cat)
                    <li class="@if(isset($category) && isset($category->slug) && ($category->slug == $cat->slug)){{ 'text-zinc-800' }}@endif"><a href="{{ route('blog.category', ['category' => $cat]) }}">{{ $cat->name }}</a></li>
                    @if(!$loop->last)
                        <li class="mx-2">&middot;</li>
                    @endif
                @endforeach
            </ul>

            <div class="grid gap-5 mx-auto mt-10 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Loop Through Posts Here -->
                @foreach($posts as $post)
                    <article id="post-{{ $post->id }}" class="overflow-hidden relative col-span-2 bg-white border border-black cursor-pointer sm:col-span-1" onClick="window.location='{{ $post->link() }}'">
                        <meta property="name" content="{{ $post->title }}">
                        <meta property="author" typeof="Person" content="admin">
                        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
                        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

                        <img src="{{ $post->image() }}" class="w-full h-auto">
                        <div class="p-5">
                            <h2 class="text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                                <a href="{{ $post->link() }}">
                                    <span class="absolute inset-0"></span>
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="mt-5 text-sm leading-6 text-gray-600 line-clamp-3">{{ substr(strip_tags($post->body), 0, 110) }}@if(strlen(strip_tags($post->body)) > 200){{ '...' }}@endif</p>
                            
                        </div>
                    </article>
                @endforeach
                <!-- End Post Loop Here -->
            </div>
        </div>

        <div class="flex justify-center my-10">
            {{ $posts->links('theme::partials.pagination') }}
        </div>

    </x-container>
</x-layouts.marketing>
