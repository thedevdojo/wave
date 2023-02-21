<?php

namespace TeamTeaTime\Forum\Actions;

use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Models\Post;

class DeletePost extends BaseAction
{
    private Post $post;
    private bool $permaDelete;

    public function __construct(Post $post, bool $permaDelete = false)
    {
        $this->post = $post;
        $this->permaDelete = $permaDelete;
    }

    protected function transact()
    {
        if ($this->permaDelete) {
            $this->post->forceDelete();
        } else {
            if ($this->post->trashed()) {
                return null;
            }

            $this->post->deleteWithoutTouch();
        }

        $lastPostInThread = $this->post->thread->getLastPost();

        $this->post->thread->updateWithoutTouch([
            'last_post_id' => $lastPostInThread->id,
            'updated_at' => $lastPostInThread->updated_at,
            'reply_count' => DB::raw('reply_count - 1'),
        ]);

        $this->post->thread->category->updateWithoutTouch([
            'latest_active_thread_id' => $this->post->thread->category->getLatestActiveThreadId(),
            'post_count' => DB::raw('post_count - 1'),
        ]);

        if ($this->permaDelete && $this->post->children !== null) {
            // Other posts reference this one; null their post IDs
            $this->post->children()->update(['post_id' => null]);
        }

        // Update sequence numbers for all of the thread's posts
        $this->post->thread->posts->each(function ($p) {
            $p->updateWithoutTouch(['sequence' => $p->getSequenceNumber()]);
        });

        return $this->post;
    }
}
