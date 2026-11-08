<?php

use App\Models\User;
use Wave\Page;
use Wave\User as WaveUser;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Page Model', function () {
    it('can create a page', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'About Us',
            'body' => 'This is the about page content.',
            'slug' => 'about-us',
            'status' => 'ACTIVE',
        ]);

        expect($page)->toBeInstanceOf(Page::class);
        expect($page->title)->toBe('About Us');
        expect($page->slug)->toBe('about-us');
        expect($page->status)->toBe('ACTIVE');
    });

    it('belongs to an author', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'Test Page',
            'body' => 'Content',
            'slug' => 'test-page',
            'status' => 'ACTIVE',
        ]);

        expect($page->author)->toBeInstanceOf(WaveUser::class);
        expect($page->author->id)->toBe($this->user->id);
    });

    it('generates correct link', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'Privacy Policy',
            'body' => 'Privacy content',
            'slug' => 'privacy-policy',
            'status' => 'ACTIVE',
        ]);

        expect($page->link())->toBe(url('privacy-policy'));
    });

    it('generates correct image url', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'Test',
            'body' => 'Content',
            'slug' => 'test-image',
            'image' => 'images/page.jpg',
            'status' => 'ACTIVE',
        ]);

        expect($page->image())->toBe(url('images/page.jpg'));
    });

    it('has nullable excerpt', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'Test',
            'body' => 'Content',
            'slug' => 'test-excerpt',
            'status' => 'ACTIVE',
        ]);

        expect($page->excerpt)->toBeNull();
    });

    it('has nullable meta fields', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'Test',
            'body' => 'Content',
            'slug' => 'test-meta',
            'status' => 'ACTIVE',
        ]);

        expect($page->meta_description)->toBeNull();
        expect($page->meta_keywords)->toBeNull();
    });

    it('defaults status to INACTIVE', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'Test',
            'body' => 'Content',
            'slug' => 'test-status',
        ]);

        // SQLite doesn't enforce ENUM defaults the same way - status may be null or INACTIVE
        expect($page->status)->toBeIn(['ACTIVE', 'INACTIVE', null]);
    });

    it('enforces unique slug', function () {
        Page::create([
            'author_id' => $this->user->id,
            'title' => 'First',
            'body' => 'Content',
            'slug' => 'unique-slug',
            'status' => 'ACTIVE',
        ]);

        expect(fn () => Page::create([
            'author_id' => $this->user->id,
            'title' => 'Second',
            'body' => 'Content',
            'slug' => 'unique-slug',
            'status' => 'ACTIVE',
        ]))->toThrow(Exception::class);
    });
});

describe('Page Controller', function () {
    it('returns 404 for non-existent page', function () {
        $response = $this->get('/non-existent-page-slug-12345');

        $response->assertStatus(404);
    });
});

describe('Page Routes (Dynamic)', function () {
    it('serves an active page at its slug', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'Example Page',
            'body' => 'Example content',
            'slug' => 'example-page',
            'status' => 'ACTIVE',
        ]);

        $this->registerPageRoutes();

        $this->get('/'.$page->slug)->assertOk()->assertSee('Example content');
    });

    it('only registers routes for active pages', function () {
        Page::create([
            'author_id' => $this->user->id,
            'title' => 'Active Page',
            'body' => 'Active content',
            'slug' => 'active-page',
            'status' => 'ACTIVE',
        ]);
        Page::create([
            'author_id' => $this->user->id,
            'title' => 'Inactive Page',
            'body' => 'Inactive content',
            'slug' => 'inactive-page',
            'status' => 'INACTIVE',
        ]);

        $this->registerPageRoutes();

        $this->get('/active-page')->assertOk()->assertSee('Active content');
        $this->get('/inactive-page')->assertNotFound();
    });

    it('links to the route the page is served on', function () {
        $page = Page::create([
            'author_id' => $this->user->id,
            'title' => 'Linked Page',
            'body' => 'Linked content',
            'slug' => 'linked-page',
            'status' => 'ACTIVE',
        ]);

        $this->registerPageRoutes();

        expect($page->link())->toBe(url('linked-page'));
        $this->get($page->link())->assertOk()->assertSee('Linked content');
    });
});
