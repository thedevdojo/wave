<?php

namespace TeamTeaTime\Forum\Events;

use Illuminate\Pagination\LengthAwarePaginator;
use TeamTeaTime\Forum\Events\Types\BaseEvent;
use TeamTeaTime\Forum\Models\Category;

class UserSearchedPosts extends BaseEvent
{
    /** @var mixed */
    public $user;

    public ?Category $category;
    public string $term;
    public LengthAwarePaginator $results;

    public function __construct($user, ?Category $category, string $term, LengthAwarePaginator $results)
    {
        $this->category = $category;
        $this->term = $term;
        $this->results = $results;
    }
}
