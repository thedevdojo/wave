<?php

namespace TeamTeaTime\Forum\Http\Requests\Bulk;

use TeamTeaTime\Forum\Actions\Bulk\UnlockThreads as Action;
use TeamTeaTime\Forum\Events\UserBulkUnlockedThreads;

class UnlockThreads extends LockThreads
{
    public function fulfill()
    {
        $action = new Action($this->validated()['threads'], $this->user()->can('viewTrashedThreads'));
        $threads = $action->execute();

        if ($threads !== null) {
            UserBulkUnlockedThreads::dispatch($this->user(), $threads);
        }

        return $threads;
    }
}
