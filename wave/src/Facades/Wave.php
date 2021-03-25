<?php

namespace Wave\Facades;

use Illuminate\Support\Facades\Facade;

class Wave extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wave';
    }
}
