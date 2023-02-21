<?php

namespace TeamTeaTime\Forum\Actions;

use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;

class DeleteCategory extends BaseAction
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    protected function transact()
    {
        $categoryIdsToDelete = [];
        $threadIdsToDelete = [];
        if (! $this->category->isEmpty()) {
            $descendantIds = $this->category->descendants->pluck('id')->toArray();
            $categoryIdsToDelete = $descendantIds;
            $threadIdsToDelete = Thread::whereIn('category_id', $descendantIds)->withTrashed()->pluck('id')->toArray();
        }

        $categoryIdsToDelete[] = $this->category->id;
        $threadIdsToDelete = array_merge($threadIdsToDelete, $this->category->threads()->withTrashed()->pluck('id')->toArray());

        Post::whereIn('thread_id', $threadIdsToDelete)->withTrashed()->forceDelete();
        DB::table(Thread::READERS_TABLE)->whereIn('thread_id', $threadIdsToDelete)->delete();
        Thread::whereIn('id', $threadIdsToDelete)->withTrashed()->forceDelete();

        return Category::whereIn('id', $categoryIdsToDelete)->delete();
    }
}
