<?php

namespace TeamTeaTime\Forum\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            'Kalnoy\Nestedset\NestedSetServiceProvider',
            'TeamTeaTime\Forum\ForumServiceProvider',
        ];
    }
}
