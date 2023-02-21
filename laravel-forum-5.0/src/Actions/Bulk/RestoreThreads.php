<?php

namespace TeamTeaTime\Forum\Actions\Bulk;

use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Actions\BaseAction;
use TeamTeaTime\Forum\Models\Thread;

class RestoreThreads extends BaseAction
{
    private array $threadIds;

    public function __construct(array $threadIds)
    {
        $this->threadIds = $threadIds;
    }

    protected function transact()
    {
        $threads = Thread::whereIn('id', $this->threadIds)->onlyTrashed()->get();

        // Return early if there are no eligible threads in the selection
        if ($threads->count() == 0) {
            return null;
        }

        // Use the raw query builder to prevent touching updated_at
        $rowsAffected = DB::table(Thread::getTableName())
            ->whereIn('id', $this->threadIds)
            ->whereNotNull(Thread::DELETED_AT)
            ->update([Thread::DELETED_AT => null]);

        if ($rowsAffected == 0) {
            return null;
        }

        $threadsByCategory = $threads->groupBy('category_id');
        foreach ($threadsByCategory as $threads) {
            $threadCount = $threads->count();
            $postCount = $threads->sum('reply_count') + $threadCount; // count the first post of each thread
            $category = $threads->first()->category;

            $category->updateWithoutTouch([
                'newest_thread_id' => max($threads->max('id'), $category->newest_thread_id),
                'latest_active_thread_id' => $category->getLatestActiveThreadId(),
                'thread_count' => DB::raw("thread_count + {$threadCount}"),
                'post_count' => DB::raw("post_count + {$postCount}"),
            ]);
        }

        return $threads;
    }
}
