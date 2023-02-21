<?php

namespace TeamTeaTime\Forum\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Post;

class SearchPosts extends BaseAction
{
    private ?Category $category;
    private string $term;

    public function __construct(?Category $category, string $term)
    {
        $this->category = $category;
        $this->term = $term;
    }

    protected function transact()
    {
        $posts = Post::orderBy('created_at', 'DESC')
            ->with('thread', 'thread.category')
            ->when($this->category, function (Builder $query) {
                $query->whereHas('thread.category', function (Builder $query) {
                    $query->where('id', $this->category->id);
                });
            })
            ->where('content', 'like', "%{$this->term}%")
            ->paginate();

        $threadIds = $posts->getCollection()->pluck('thread')->filter(function ($thread) {
            return ! $thread->category->is_private || Gate::allows('view', $thread->category) && Gate::allows('view', $thread);
        })->pluck('id')->unique();

        $posts->setCollection($posts->getCollection()->filter(function ($post) use ($threadIds) {
            return $threadIds->contains($post->thread->id);
        }));

        return $posts;
    }
}
