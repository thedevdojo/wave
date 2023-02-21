<?php

namespace TeamTeaTime\Forum\Events\Types;

use TeamTeaTime\Forum\Models\Category;

class CategoryEvent extends BaseEvent
{
    /** @var mixed */
    public $user;

    public Category $category;

    public function __construct($user, Category $category)
    {
        $this->user = $user;
        $this->category = $category;
    }
}
