<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Wave\Category;

new
#[Layout('theme::components.layouts.marketing')]
class extends Component
{
    public Category $category;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    #[Computed]
    public function posts()
    {
        return $this->category->posts()->paginate(6);
    }
}

?>

<div>
    <x-container>
        <div class="relative pt-6">
            <x-marketing.elements.heading
                title="{{ $category->name }} Articles"
                description="Our latest {{ $category->name }} posts below."
                align="left"
            />

            @include('theme::partials.blog.categories')

            <div class="grid gap-5 mx-auto mt-7 sm:grid-cols-2 lg:grid-cols-3">
                @include('theme::partials.blog.posts-loop', ['posts' => $this->posts])
            </div>
        </div>

        <div class="flex justify-center my-10">
            {{ $this->posts->links('theme::partials.pagination') }}
        </div>
    </x-container>
</div>
