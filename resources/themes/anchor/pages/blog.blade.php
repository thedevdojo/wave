<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Wave\Category;
use Wave\Post;

new
#[Layout('theme::components.layouts.marketing')]
class extends Component
{
    #[Computed]
    public function posts()
    {
        return Post::where('status', 'PUBLISHED')->orderBy('created_at', 'DESC')->paginate(6);
    }

    #[Computed]
    public function categories()
    {
        return Category::all();
    }
}

?>

<div>
    <x-container>
        <div class="relative pt-6">
            <x-marketing.elements.heading
                title="From The Blog"
                description="Check out some of our latest blog posts below."
                align="left"
            />

            @include('theme::partials.blog.categories')

            <div class="grid gap-5 mx-auto mt-5 md:mt-10 sm:grid-cols-2 lg:grid-cols-3">
                @include('theme::partials.blog.posts-loop', ['posts' => $this->posts])
            </div>
        </div>

        <div class="flex justify-center my-10">
            {{ $this->posts->links('theme::partials.pagination') }}
        </div>
    </x-container>
</div>
