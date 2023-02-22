<?php

namespace HansSchouten\LaravelPageBuilder;

/**
 * @see \HansSchouten\LaravelPageBuilder\LaravelPageBuilder
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return LaravelPageBuilder::class;
    }
}
