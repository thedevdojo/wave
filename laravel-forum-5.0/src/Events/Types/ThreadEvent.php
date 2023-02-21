<?php

namespace TeamTeaTime\Forum\Events\Types;

use TeamTeaTime\Forum\Models\Thread;

class ThreadEvent extends BaseEvent
{
    /** @var mixed */
    public $user;

    public Thread $thread;

    public function __construct($user, Thread $thread)
    {
        $this->user = $user;
        $this->thread = $thread;
    }
}
