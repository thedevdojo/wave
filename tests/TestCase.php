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
        if (\Illuminate\Support\Facades\Schema::hasTable('users') &&
            ! \App\Models\User::where('email', 'admin@admin.com')->exists()) {
            Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        }

        // Manually register Folio path for anchor theme so all settings/folio routes are defined in tests
        if (class_exists(\Laravel\Folio\Folio::class) && file_exists(resource_path('themes/anchor/pages'))) {
            \Laravel\Folio\Folio::path(resource_path('themes/anchor/pages'))->middleware(['*']);
        }

        // Manually register view namespace and components for the anchor theme in tests
        if (file_exists(resource_path('themes/anchor'))) {
            view()->addNamespace('theme', resource_path('themes/anchor'));
            \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('themes/anchor/components/elements'));
            \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('themes/anchor/components'));
        }
    }
}
