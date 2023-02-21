<?php

namespace TeamTeaTime\Forum\Http\Requests;

use TeamTeaTime\Forum\Actions\UnpinThread as Action;
use TeamTeaTime\Forum\Events\UserUnpinnedThread;

class UnpinThread extends PinThread
{
    public function fulfill()
    {
        $action = new Action($this->route('thread'));
        $thread = $action->execute();

        if ($thread !== null) {
            UserUnpinnedThread::dispatch($this->user(), $thread);
        }

        return $thread;
    }
}
