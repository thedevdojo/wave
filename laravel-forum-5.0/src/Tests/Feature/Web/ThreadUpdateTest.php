<?php

namespace TeamTeaTime\Forum\Tests\Feature\Web;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use Orchestra\Testbench\Factories\UserFactory;
use TeamTeaTime\Forum\Database\Factories\CategoryFactory;
use TeamTeaTime\Forum\Database\Factories\PostFactory;
use TeamTeaTime\Forum\Database\Factories\ThreadFactory;
use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Support\Web\Forum;
use TeamTeaTime\Forum\Tests\FeatureTestCase;

class ThreadUpdateTest extends FeatureTestCase
{
    private const ROUTE_LOCK = 'thread.lock';
    private const ROUTE_UNLOCK = 'thread.unlock';
    private const ROUTE_PIN = 'thread.pin';
    private const ROUTE_UNPIN = 'thread.unpin';
    private const ROUTE_MOVE = 'thread.move';
    private const ROUTE_RENAME = 'thread.rename';

    private CategoryFactory $categoryFactory;

    private Thread $thread;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $postFactory = PostFactory::new();
        $threadFactory = ThreadFactory::new();
        $userFactory = UserFactory::new();

        $this->categoryFactory = CategoryFactory::new();

        $this->user = $userFactory->createOne();
        $this->thread = $threadFactory->createOne(['author_id' => $this->user->getKey()]);
        $postFactory->createOne(['thread_id' => $this->thread->getKey()]);

        // Make Gate fully permissive
        Gate::before(fn () => true);
    }

    /** @test */
    public function should_lock_when_receive_lock_action()
    {
        $this->actingAs($this->user)->post(Forum::route(self::ROUTE_LOCK, $this->thread), []);

        $thread = Thread::find($this->thread->getKey());

        $this->assertEquals(1, $thread->locked);
    }

    /** @test */
    public function should_unlock_when_receive_unlock_action()
    {
        $this->actingAs($this->user)->post(Forum::route(self::ROUTE_LOCK, $this->thread), []);
        $this->actingAs($this->user)->post(Forum::route(self::ROUTE_UNLOCK, $this->thread), []);

        $thread = Thread::find($this->thread->getKey());

        $this->assertEquals(0, $thread->locked);
    }

    /** @test */
    public function should_pin_when_receive_pin_action()
    {
        $this->actingAs($this->user)->post(Forum::route(self::ROUTE_PIN, $this->thread), []);

        $thread = Thread::find($this->thread->getKey());

        $this->assertEquals(1, $thread->pinned);
    }

    /** @test */
    public function should_unpin_when_receive_unpin_action()
    {
        $this->actingAs($this->user)->post(Forum::route(self::ROUTE_PIN, $this->thread), []);
        $this->actingAs($this->user)->post(Forum::route(self::ROUTE_UNPIN, $this->thread), []);

        $thread = Thread::find($this->thread->getKey());

        $this->assertEquals(0, $thread->pinned);
    }

    /** @test */
    public function should_have_destination_category_after_move()
    {
        $destinationCategory = $this->categoryFactory->createOne();

        $this->actingAs($this->user)
            ->post(Forum::route(self::ROUTE_MOVE, $this->thread), ['category_id' => $destinationCategory->getKey()]);

        $thread = Thread::find($this->thread->getKey());

        $this->assertEquals($destinationCategory->id, $thread->category_id);
    }

    /** @test */
    public function should_fail_validation_with_empty_title_when_renaming()
    {
        $response = $this->actingAs($this->user)
            ->post(Forum::route(self::ROUTE_RENAME, $this->thread), ['title' => '']);

        $response->assertSessionHasErrors();
    }
}
