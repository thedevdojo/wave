<?php

use App\Models\User;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ChangelogsTableSeeder;
use Database\Seeders\PagesTableSeeder;
use Database\Seeders\PlansTableSeeder;
use Database\Seeders\PostsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Spatie\Permission\Models\Role;
use Wave\Category;
use Wave\Changelog;
use Wave\Page;
use Wave\Plan;
use Wave\Post;

// The seeders insert explicit ids, which Postgres doesn't count towards its id sequences
it('can create rows after seeding the tables with explicit ids', function () {
    $this->seed([UsersTableSeeder::class, CategoriesTableSeeder::class, PlansTableSeeder::class, PostsTableSeeder::class, PagesTableSeeder::class, ChangelogsTableSeeder::class]);

    Role::create(['name' => 'editor', 'guard_name' => 'web']);
    User::factory()->create();
    Category::create(['name' => 'News', 'slug' => 'news']);
    Plan::create(['name' => 'Team', 'features' => '', 'role_id' => Role::findByName('premium')->id]);
    Post::create(['author_id' => 1, 'title' => 'Hello', 'slug' => 'hello-new', 'body' => 'Hi', 'status' => 'PUBLISHED']);
    Page::create(['author_id' => 1, 'title' => 'New', 'slug' => 'new-page', 'body' => 'Hi', 'status' => 'ACTIVE']);
    Changelog::create(['title' => 'New', 'description' => 'Hi', 'body' => 'Hi']);

    expect(Role::count())->toBe(6);
});
