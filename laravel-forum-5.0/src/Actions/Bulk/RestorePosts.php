<?php

namespace TeamTeaTime\Forum\Actions\Bulk;

use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Actions\BaseAction;
use TeamTeaTime\Forum\Models\Post;

class RestorePosts extends BaseAction
{
    private array $postIds;

    public function __construct(array $postIds)
    {
        $this->postIds = $postIds;
    }

    protected function transact()
    {
        $posts = Post::whereIn('id', $this->postIds)->onlyTrashed()->get();

        // Return early if there are no eligible threads in the selection
        if ($posts->count() == 0) {
            return null;
        }

        // Use the raw query builder to prevent touching updated_at
        $rowsAffected = DB::table(Post::getTableName())
            ->whereIn('id', $this->postIds)
            ->whereNotNull(Post::DELETED_AT)
            ->update([Post::DELETED_AT => null]);

        if ($rowsAffected == 0) {
            return null;
        }

        $threads = $posts->pluck('thread')->unique();
        $postsByThread = $posts->groupBy('thread_id');

        foreach ($threads as $thread) {
            $threadPosts = $postsByThread->get($thread->id);
            $thread->updateWithoutTouch([
                'last_post_id' => $thread->getLastPost()->id,
                'reply_count' => DB::raw("reply_count + {$threadPosts->count()}"),
            ]);
        }

        $categories = $threads->pluck('category')->unique();
        $threadsByCategory = $threads->groupBy('category_id');

        foreach ($categories as $category) {
            $categoryThreads = $threadsByCategory->get($category->id);
            $postCount = $posts->whereIn('thread_id', $categoryThreads->pluck('id'))->count();
            $category->updateWithoutTouch([
                'latest_active_thread_id' => $category->getLatestActiveThreadId(),
                'post_count' => DB::raw("post_count + {$postCount}"),
            ]);
        }

        return $posts;
    }
}
