<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Wave\Category;
use Wave\Post;

new
#[Layout('theme::components.layouts.marketing')]
class extends Component
{
    public Category $category;

    public Post $post;

    public function mount(Category $category, Post $post): void
    {
        if ($post->category_id !== $category->id) {
            abort(404);
        }

        $this->category = $category;
        $this->post = $post;
    }
}

?>

<div>
    <article id="post-{{ $post->id }}" class="max-w-4xl px-5 pb-20 mx-auto prose prose-md dark:prose-invert lg:prose-lg lg:px-0">

        <x-elements.back-button
            class="max-w-4xl mx-auto mt-4 md:mt-8"
            text="back to the blog"
            :href="route('blog')"
        />

        <meta property="name" content="{{ $post->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

        <div class="max-w-4xl mx-auto mt-6">
            <h1 class="flex flex-col leading-none">
                <span>{{ $post->title }}</span>
            </h1>
        </div>

        <div class="relative">
            <img class="w-full h-auto rounded-lg" src="{{ $post->image() }}" alt="{{ $post->title }}" srcset="{{ $post->image() }}">
        </div>

        <div class="max-w-4xl mx-auto">
            {!! $post->body !!}
        </div>

    </article>
</div>
