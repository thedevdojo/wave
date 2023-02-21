<?php

namespace TeamTeaTime\Forum\Actions;

use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Models\Post;

class RestorePost extends BaseAction
{
    private Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    protected function transact()
    {
        if (! $this->post->trashed()) {
            return null;
        }

        $this->post->restoreWithoutTouch();

        $this->post->thread->updateWithoutTouch([
            'last_post_id' => max($this->post->id, $this->post->thread->last_post_id),
            'reply_count' => DB::raw('reply_count + 1'),
        ]);

        $this->post->thread->category->updateWithoutTouch([
            'latest_active_thread_id' => $this->post->thread->category->getLatestActiveThreadId(),
            'post_count' => DB::raw('post_count + 1'),
        ]);

        return $this->post;
    }
}
