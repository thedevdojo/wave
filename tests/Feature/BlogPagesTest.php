<?php

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Wave\Category;
use Wave\Changelog;
use Wave\Post;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

describe('Post admin', function () {
    beforeEach(function () {
        $this->actingAs(User::factory()->admin()->create());
    });

    it('fills the slug from the title when creating a post', function () {
        Livewire::test(CreatePost::class)
            ->fillForm(['title' => 'My First Post'])
            ->assertSchemaStateSet(['slug' => 'my-first-post']);
    });

    it('keeps the slug when the title of an existing post changes', function () {
        $post = Post::factory()->create(['category_id' => $this->category->id, 'slug' => 'original-slug']);

        Livewire::test(EditPost::class, ['record' => $post->id])
            ->fillForm(['title' => 'Typo fixed title'])
            ->assertSchemaStateSet(['slug' => 'original-slug'])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($post->fresh()->slug)->toBe('original-slug');
    });
});

describe('Post page', function () {
    it('uses the post SEO fields in the head', function () {
        $post = Post::factory()->create([
            'category_id' => $this->category->id,
            'seo_title' => 'My SEO Title',
            'meta_description' => 'My meta description',
        ]);

        $this->get($post->link())
            ->assertOk()
            ->assertSee('<title>My SEO Title</title>', false)
            ->assertSee('<meta name="description" content="My meta description">', false)
            ->assertSee('<meta property="og:image" content="'.$post->image().'">', false);
    });

    it('falls back to the title and excerpt without SEO fields', function () {
        $post = Post::factory()->create([
            'category_id' => $this->category->id,
            'title' => 'Plain Title',
            'excerpt' => 'Plain excerpt',
            'seo_title' => null,
            'meta_description' => null,
        ]);

        $this->get($post->link())
            ->assertOk()
            ->assertSee('<title>Plain Title</title>', false)
            ->assertSee('<meta name="description" content="Plain excerpt">', false);
    });

    it('leaves out the image when the post has none', function () {
        $post = Post::factory()->create(['category_id' => $this->category->id, 'image' => null]);

        expect($post->image())->toBeNull();

        $this->get($post->link())->assertOk()->assertDontSee('/storage/"', false);
        $this->get(route('blog'))->assertOk()->assertDontSee('/storage/"', false);
    });
});

describe('Blog index', function () {
    it('shows when a post was published', function () {
        $post = Post::factory()->create(['category_id' => $this->category->id]);
        $post->forceFill(['created_at' => '2025-01-15 10:00:00', 'updated_at' => '2026-06-01 10:00:00'])->save();

        $this->get(route('blog'))
            ->assertOk()
            ->assertSee('<time datetime="2025-01-15" class="text-zinc-500">Jan 15, 2025</time>', false);
    });

    it('lists categories in their admin order', function () {
        Category::factory()->create(['name' => 'Second Category', 'order' => 3]);
        Category::factory()->create(['name' => 'First Category', 'order' => 2]);

        $this->get(route('blog'))->assertOk()->assertSeeInOrder(['First Category', 'Second Category']);
    });

    it('titles category pages after the category', function () {
        $category = Category::factory()->create(['name' => 'Tutorials']);

        $this->get(route('blog.category', ['category' => $category]))
            ->assertOk()
            ->assertSee('<title>Tutorials Articles</title>', false);
    });

    it('loads post categories in one query on the listing pages', function () {
        Post::factory()->count(3)->create(['category_id' => $this->category->id]);
        Post::factory()->create(['category_id' => Category::factory()->create()->id]);

        $categoryQueries = function (string $url) {
            $count = 0;
            DB::listen(function ($query) use (&$count) {
                if (preg_match('/from [`"]?categories[`"]? where [`"]?categories[`"]?\.[`"]?id[`"]? (=|in)/i', $query->sql)) {
                    $count++;
                }
            });
            $this->get($url)->assertOk();

            return $count;
        };

        expect($categoryQueries(route('blog')))->toBe(1);
        expect($categoryQueries(route('blog.category', ['category' => $this->category])))->toBe(1);
    });
});

describe('Changelog page', function () {
    it('links to older entries', function () {
        foreach (range(1, 11) as $i) {
            $changelog = Changelog::create(['title' => "Release $i", 'description' => 'Changes', 'body' => '<p>Changes</p>']);
            $changelog->forceFill(['created_at' => now()->subDays(20 - $i)])->save();
        }

        $this->get(route('changelogs'))
            ->assertOk()
            ->assertSee('Release 11')
            ->assertDontSee('Release 1<')
            ->assertSee('?page=2', false);

        $this->get(route('changelogs').'?page=2')->assertOk()->assertSee('Release 1');
    });
});
