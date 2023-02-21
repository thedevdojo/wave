<?php

namespace TeamTeaTime\Forum\Events;

use Illuminate\Database\Eloquent\Collection;
use TeamTeaTime\Forum\Events\Types\CollectionEvent;
use TeamTeaTime\Forum\Models\Category;

class UserMarkedThreadsAsRead extends CollectionEvent
{
    public ?Category $category;

    public function __construct($user, ?Category $category, Collection $threads)
    {
        parent::__construct($user, $threads);

        $this->category = $category;
    }
}
