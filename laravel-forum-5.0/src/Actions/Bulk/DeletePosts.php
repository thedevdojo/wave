<?php

namespace TeamTeaTime\Forum\Actions\Bulk;

use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Actions\BaseAction;
use TeamTeaTime\Forum\Models\Post;

class DeletePosts extends BaseAction
{
    private array $postIds;
    private bool $includeTrashed;
    private bool $permaDelete;

    public function __construct(array $postIds, bool $includeTrashed, bool $permaDelete = false)
    {
        $this->postIds = $postIds;
        $this->includeTrashed = $includeTrashed;
        $this->permaDelete = $permaDelete;
    }

    protected function transact()
    {
        $query = Post::whereIn('id', $this->postIds);

        if ($this->includeTrashed) {
            $posts = $query->withTrashed()->get();

            // Return early if this is a soft-delete and the selected posts are already trashed,
            // or there are no valid posts in the selection
            if (! $this->permaDelete && $posts->whereNull(Post::DELETED_AT)->count() == 0) {
                return null;
            }
        } else {
            $posts = $query->get();

            // Return early if there are no valid posts in the selection
            if ($posts->count() == 0) {
                return null;
            }
        }

        $rowsAffected = $this->permaDelete
            ? $query->forceDelete()
            : $query->delete();

        if ($rowsAffected == 0) {
            return null;
        }

        $threads = $posts->pluck('thread')->unique();
        $categories = $threads->pluck('category')->unique();

        foreach ($categories as $category) {
            $categoryThreadsRemoved = 0;
            $categoryPostsRemoved = 0;

            foreach ($threads->where('category_id', $category->id) as $thread) {
                $threadPostsRemoved = $posts->where('thread_id', $thread->id)->whereNull('deleted_at')->count();
                $categoryPostsRemoved += $threadPostsRemoved;

                // Skip updates if the affected posts were already soft-deleted
                // or there were no valid post IDs given for this thread
                if ($threadPostsRemoved == 0) {
                    continue;
                }

                if ($thread->posts()->count() == 0) {
                    if (! $thread->trashed()) {
                        // Thread has not been soft-deleted already;
                        // it should count towards threads removed for this category
                        $categoryThreadsRemoved++;
                    }

                    if ($thread->posts()->withTrashed()->count() == 0) {
                        $thread->forceDelete();
                    } else {
                        $thread->delete();
                    }
                } else {
                    $thread->updateWithoutTouch([
                        'last_post_id' => $thread->getLastPost()->id,
                        'reply_count' => DB::raw("reply_count - {$threadPostsRemoved}"),
                    ]);

                    $thread->posts->each(function ($p) {
                        $p->updateWithoutTouch(['sequence' => $p->getSequenceNumber()]);
                    });
                }
            }

            $attributes = [
                'latest_active_thread' => $category->getLatestActiveThreadId(),
            ];

            if ($categoryThreadsRemoved > 0) {
                $attributes['thread_count'] = DB::raw("thread_count - {$categoryThreadsRemoved}");
            }

            if ($categoryPostsRemoved > 0) {
                $attributes['post_count'] = DB::raw("post_count - {$categoryPostsRemoved}");
            }

            $category->updateWithoutTouch($attributes);
        }

        return $posts;
    }
}
