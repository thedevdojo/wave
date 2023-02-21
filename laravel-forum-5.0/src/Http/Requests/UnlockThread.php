<?php

namespace TeamTeaTime\Forum\Http\Requests;

use TeamTeaTime\Forum\Actions\UnlockThread as Action;
use TeamTeaTime\Forum\Events\UserUnlockedThread;

class UnlockThread extends LockThread
{
    public function fulfill()
    {
        $action = new Action($this->route('thread'));
        $thread = $action->execute();

        if ($thread !== null) {
            UserUnlockedThread::dispatch($this->user(), $thread);
        }

        return $thread;
    }
}
