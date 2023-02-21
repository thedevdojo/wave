<?php

namespace TeamTeaTime\Forum\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Thread;

class CreateThread extends BaseAction
{
    private Category $category;
    private Model $author;
    private string $title;
    private string $content;

    public function __construct(Category $category, Model $author, string $title, string $content)
    {
        $this->category = $category;
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
    }

    protected function transact()
    {
        $thread = Thread::create([
            'author_id' => $this->author->getKey(),
            'category_id' => $this->category->id,
            'title' => $this->title,
        ]);

        $post = $thread->posts()->create([
            'author_id' => $this->author->getKey(),
            'content' => $this->content,
            'sequence' => 1,
        ]);

        $thread->update([
            'first_post_id' => $post->id,
            'last_post_id' => $post->id,
        ]);

        $thread->category->updateWithoutTouch([
            'newest_thread_id' => $thread->id,
            'latest_active_thread_id' => $thread->id,
            'thread_count' => DB::raw('thread_count + 1'),
            'post_count' => DB::raw('post_count + 1'),
        ]);

        return $thread;
    }
}
