<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Laravel\Folio\Folio;
use Wave\Category;
use Wave\Post;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

function visibilityPost(Category $category, array $attributes = []): Post
{
    return Post::factory()->create(array_merge([
        'category_id' => $category->id,
        'title' => 'Visibility Test '.uniqid(),
    ], $attributes));
}

describe('Single post page', function () {
    it('shows a published post', function () {
        $post = visibilityPost($this->category);

        $this->get($post->link())->assertOk()->assertSee($post->title);
    });

    it('returns 404 for draft and pending posts', function (string $status) {
        $post = visibilityPost($this->category, ['status' => $status]);

        $this->get($post->link())->assertNotFound();
    })->with(['DRAFT', 'PENDING']);

    it('returns 404 for draft posts to non-admin users', function () {
        $user = User::factory()->create();
        $post = visibilityPost($this->category, ['status' => 'DRAFT']);

        $this->actingAs($user)->get($post->link())->assertNotFound();
    });

    it('lets admins preview draft posts', function () {
        $admin = User::factory()->admin()->create();
        $post = visibilityPost($this->category, ['status' => 'DRAFT']);

        $this->actingAs($admin)->get($post->link())->assertOk()->assertSee($post->title);
    });

    // Folio binds the post before the session starts, so actingAs() alone would hide a broken check.
    it('lets admins preview draft posts with a real session', function () {
        $admin = User::factory()->admin()->create();
        $post = visibilityPost($this->category, ['status' => 'DRAFT']);

        $sessionId = str_repeat('a', 40);
        app('session')->driver()->getHandler()->write($sessionId, serialize([
            Auth::guard('web')->getName() => $admin->id,
        ]));

        $this->withCookie(config('session.cookie'), $sessionId)
            ->get($post->link())
            ->assertOk()
            ->assertSee($post->title);
    });

    it('hides drafts on theme pages that do not check the status', function () {
        $pages = sys_get_temp_dir().'/wave-folio-'.uniqid();
        File::ensureDirectoryExists($pages);
        File::put($pages.'/[.Wave.Post-slug].blade.php', '<h1>{{ $post->title }}</h1>');
        $this->beforeApplicationDestroyed(fn () => File::deleteDirectory($pages));

        Folio::path($pages)->uri('/custom-theme');

        $published = visibilityPost($this->category);
        $draft = visibilityPost($this->category, ['status' => 'DRAFT']);

        $this->get('/custom-theme/'.$published->slug)->assertOk()->assertSee($published->title);
        $this->get('/custom-theme/'.$draft->slug)->assertNotFound();
        $this->actingAs(User::factory()->admin()->create())
            ->get('/custom-theme/'.$draft->slug)
            ->assertOk();
    });
});

describe('Category page', function () {
    it('only lists published posts', function () {
        $published = visibilityPost($this->category);
        $draft = visibilityPost($this->category, ['status' => 'DRAFT']);
        $pending = visibilityPost($this->category, ['status' => 'PENDING']);

        $this->get('/blog/'.$this->category->slug)
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee($draft->title)
            ->assertDontSee($pending->title);
    });

    it('lists the newest posts first', function () {
        $older = visibilityPost($this->category, ['created_at' => now()->subDays(2)]);
        $newer = visibilityPost($this->category, ['created_at' => now()->subDay()]);

        $this->get('/blog/'.$this->category->slug)
            ->assertOk()
            ->assertSeeInOrder([$newer->title, $older->title]);
    });
});

describe('Blog index', function () {
    it('does not list draft posts', function () {
        $draft = visibilityPost($this->category, ['status' => 'DRAFT']);

        $this->get('/blog')->assertOk()->assertDontSee($draft->title);
    });

    it('still renders after a category with posts is deleted', function () {
        $post = visibilityPost($this->category);

        $this->category->delete();

        expect($post->fresh()->category_id)->toBeNull();

        $this->get('/blog')->assertOk()->assertDontSee($post->title);
    });

    it('shows categories added after the list was cached', function () {
        $this->get('/blog')->assertOk();

        $added = Category::factory()->create();

        $this->get('/blog')->assertOk()->assertSee($added->name);

        $added->delete();

        $this->get('/blog')->assertOk()->assertDontSee($added->name);
    });
});

describe('Post excerpt', function () {
    it('truncates long bodies with an ellipsis', function () {
        visibilityPost($this->category, ['body' => '<p>'.str_repeat('a', 150).'</p>']);

        $this->get('/blog/'.$this->category->slug)
            ->assertOk()
            ->assertSee(str_repeat('a', 110).'...', false);
    });

    it('does not split multibyte characters', function () {
        visibilityPost($this->category, ['body' => '<p>'.str_repeat('é', 150).'</p>']);

        $this->get('/blog/'.$this->category->slug)
            ->assertOk()
            ->assertSee(str_repeat('é', 110).'...', false);
    });

    it('does not add an ellipsis to short bodies', function () {
        visibilityPost($this->category, ['body' => '<p>Short body.</p>']);

        $this->get('/blog/'.$this->category->slug)
            ->assertOk()
            ->assertSee('Short body.</p>', false);
    });
});
