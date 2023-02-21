<?php

namespace TeamTeaTime\Forum\Actions;

use Illuminate\Support\Facades\DB;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Thread;

class MoveThread extends BaseAction
{
    private Thread $thread;
    private Category $destinationCategory;

    public function __construct(Thread $thread, Category $destinationCategory)
    {
        $this->thread = $thread;
        $this->destinationCategory = $destinationCategory;
    }

    protected function transact()
    {
        $sourceCategory = $this->thread->category;

        if ($sourceCategory->id === $this->destinationCategory->id) {
            return null;
        }

        $this->thread->updateWithoutTouch(['category_id' => $this->destinationCategory->id]);

        $sourceCategoryValues = [];

        if ($sourceCategory->newest_thread_id === $this->thread->id) {
            $sourceCategoryValues['newest_thread_id'] = $sourceCategory->getNewestThreadId();
        }
        if ($sourceCategory->latest_active_thread_id === $this->thread->id) {
            $sourceCategoryValues['latest_active_thread_id'] = $sourceCategory->getLatestActiveThreadId();
        }

        $sourceCategoryValues['thread_count'] = DB::raw('thread_count - 1');
        $sourceCategoryValues['post_count'] = DB::raw("post_count - {$this->thread->postCount}");

        $sourceCategory->updateWithoutTouch($sourceCategoryValues);

        $this->destinationCategory->updateWithoutTouch([
            'thread_count' => DB::raw('thread_count + 1'),
            'post_count' => DB::raw("post_count + {$this->thread->postCount}"),
            'newest_thread_id' => $this->destinationCategory->getNewestThreadId(),
            'latest_active_thread_id' => $this->destinationCategory->getLatestActiveThreadId(),
        ]);

        return $this->thread;
    }
}
