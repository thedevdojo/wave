<?php

namespace TeamTeaTime\Forum\Tests\Feature\Web;

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\Factories\UserFactory;
use TeamTeaTime\Forum\Database\Factories\PostFactory;
use TeamTeaTime\Forum\Database\Factories\ThreadFactory;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Support\Web\Forum;
use TeamTeaTime\Forum\Tests\FeatureTestCase;

class ThreadDeleteTest extends FeatureTestCase
{
    private const ROUTE = 'thread.delete';

    private ThreadFactory $threadFactory;
    private PostFactory $postFactory;

    private User $user;
    private Thread $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->threadFactory = ThreadFactory::new();
        $this->postFactory = PostFactory::new();

        $this->user = UserFactory::new()->createOne();
        $this->thread = $this->threadFactory->createOne(['author_id' => $this->user->getKey()]);
    }

    /** @test */
    public function should_302_when_not_logged_in()
    {
        $response = $this->delete(Forum::route(self::ROUTE, $this->thread), []);
        $response->assertStatus(302);
    }

    /** @test */
    public function should_404_with_invalid_thread_id()
    {
        $thread = $this->thread;
        $thread->id++;
        $response = $this->actingAs($this->user)
            ->delete(Forum::route(self::ROUTE, $thread), []);
        $response->assertStatus(404);
    }

    /** @test */
    public function should_soft_delete_by_default()
    {
        $thread = $this->threadFactory->createOne(['author_id' => $this->user->getKey()]);
        $this->postFactory->createOne(['thread_id' => $thread->getKey(), 'author_id' => $this->user->getKey()]);

        $this->actingAs($this->user)->delete(Forum::route(self::ROUTE, $thread), []);

        // Accounting for $this->thread
        $this->assertEquals(2, Thread::withTrashed()->count());
    }

    /** @test */
    public function should_delete_all_posts_inside_the_thread_when_perma_deleting()
    {
        $thread = $this->threadFactory->createOne(['author_id' => $this->user->getKey()]);
        $this->postFactory->count(2)->create(['thread_id' => $thread->getKey(), 'author_id' => $this->user->getKey()]);

        $this->actingAs($this->user)->delete(Forum::route(self::ROUTE, $thread), ['permadelete' => true]);

        $this->assertEquals(0, Post::count());
    }
}
