<?php

namespace TeamTeaTime\Forum\Events;

use TeamTeaTime\Forum\Events\Types\ThreadEvent;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Thread;

class UserMovedThread extends ThreadEvent
{
    public Category $destinationCategory;

    public function __construct($user, Thread $thread, Category $destinationCategory)
    {
        parent::__construct($user, $thread);

        $this->destinationCategory = $destinationCategory;
    }
}
