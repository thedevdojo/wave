<?php

namespace TeamTeaTime\Forum\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;

class CreatePost extends BaseAction
{
    private Thread $thread;
    private ?Post $parent;
    private Model $author;
    private string $content;

    public function __construct(Thread $thread, ?Post $parent, Model $author, string $content)
    {
        $this->thread = $thread;
        $this->parent = $parent;
        $this->author = $author;
        $this->content = $content;
    }

    protected function transact()
    {
        $post = $this->thread->posts()->create([
            'post_id' => $this->parent === null ? null : $this->parent->id,
            'author_id' => $this->author->getKey(),
            'sequence' => $this->thread->posts->count() + 1,
            'content' => $this->content,
        ]);

        $this->thread->update([
            'last_post_id' => $post->id,
            'reply_count' => DB::raw('reply_count + 1'),
        ]);

        $this->thread->category->updateWithoutTouch([
            'latest_active_thread_id' => $this->thread->id,
            'post_count' => DB::raw('post_count + 1'),
        ]);

        return $post;
    }
}
