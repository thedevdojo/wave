<?php

namespace TeamTeaTime\Forum\Tests\Feature\Web;

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\Factories\UserFactory;
use TeamTeaTime\Forum\Database\Factories\PostFactory;
use TeamTeaTime\Forum\Database\Factories\ThreadFactory;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Support\Web\Forum;
use TeamTeaTime\Forum\Tests\FeatureTestCase;

class PostUpdateTest extends FeatureTestCase
{
    private const ROUTE = 'post.update';

    private Post $post;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $postFactory = PostFactory::new();
        $threadFactory = ThreadFactory::new();
        $userFactory = UserFactory::new();

        $this->user = $userFactory->createOne();
        $thread = $threadFactory->createOne(['author_id' => $this->user->getKey()]);
        $this->post = $postFactory->createOne(['thread_id' => $thread->getKey(), 'author_id' => $this->user->getKey()]);
    }

    /** @test */
    public function should_fail_validation_with_empty_content()
    {
        $response = $this->actingAs($this->user)
            ->patch(Forum::route(self::ROUTE, $this->post), ['content' => '']);

        $response->assertSessionHasErrors();
    }
}
