<?php

namespace TeamTeaTime\Forum\Http\Requests\Bulk;

use TeamTeaTime\Forum\Actions\Bulk\UnpinThreads as Action;
use TeamTeaTime\Forum\Events\UserBulkUnpinnedThreads;

class UnpinThreads extends PinThreads
{
    public function fulfill()
    {
        $action = new Action($this->validated()['threads'], $this->user()->can('viewTrashedThreads'));
        $threads = $action->execute();

        if ($threads !== null) {
            UserBulkUnpinnedThreads::dispatch($this->user(), $threads);
        }

        return $threads;
    }
}
