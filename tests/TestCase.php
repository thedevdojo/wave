<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seed the database after migrations for tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        if (Schema::hasTable('roles') &&
            ! Role::where('name', 'registered')->exists()) {
            Artisan::call('db:seed', ['--class' => 'RolesTableSeeder']);
        }
    }
}
