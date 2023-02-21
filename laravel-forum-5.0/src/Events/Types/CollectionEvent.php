<?php

namespace TeamTeaTime\Forum\Events\Types;

use Illuminate\Support\Collection;

class CollectionEvent extends BaseEvent
{
    /** @var mixed */
    public $user;

    public Collection $collection;

    public function __construct($user, Collection $collection)
    {
        $this->user = $user;
        $this->collection = $collection;
    }
}
