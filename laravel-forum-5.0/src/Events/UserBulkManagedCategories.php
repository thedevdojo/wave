<?php

namespace TeamTeaTime\Forum\Events;

use TeamTeaTime\Forum\Events\Types\BaseEvent;

class UserBulkManagedCategories extends BaseEvent
{
    /** @var mixed */
    public $user;

    public int $categoriesAffected;
    public array $categoryData;

    public function __construct($user, int $categoriesAffected, array $categoryData)
    {
        $this->user = $user;
        $this->categoriesAffected = $categoriesAffected;
        $this->categoryData = $categoryData;
    }
}
