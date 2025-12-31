<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seed the database after migrations for tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        if (\Illuminate\Support\Facades\Schema::hasTable('roles') &&
            ! \Spatie\Permission\Models\Role::where('name', 'registered')->exists()) {
            Artisan::call('db:seed', ['--class' => 'RolesTableSeeder']);
        }
    }
}
