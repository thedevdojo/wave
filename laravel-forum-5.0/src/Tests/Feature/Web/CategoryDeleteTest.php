<?php

namespace TeamTeaTime\Forum\Tests\Feature\Web;

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\Factories\UserFactory;
use TeamTeaTime\Forum\Database\Factories\CategoryFactory;
use TeamTeaTime\Forum\Database\Factories\PostFactory;
use TeamTeaTime\Forum\Database\Factories\ThreadFactory;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Support\Web\Forum;
use TeamTeaTime\Forum\Tests\FeatureTestCase;

class CategoryDeleteTest extends FeatureTestCase
{
    private const ROUTE = 'category.delete';

    private UserFactory $userFactory;
    private User $user;

    private CategoryFactory $categoryFactory;
    private ThreadFactory $threadFactory;
    private PostFactory $postFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userFactory = UserFactory::new();
        $this->user = $this->userFactory->createOne();

        $this->categoryFactory = CategoryFactory::new();
        $this->threadFactory = ThreadFactory::new();
        $this->postFactory = PostFactory::new();
    }

    /** @test */
    public function should_fail_validation_without_force_param_if_not_empty()
    {
        $topLevelCategory = $this->seedCategories();

        $response = $this->actingAs($this->user)
            ->delete(Forum::route(self::ROUTE, $topLevelCategory));

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function should_remove_descendant_content_upon_force_deletion()
    {
        $topLevelCategory = $this->seedCategories();

        $this->actingAs($this->user)
            ->delete(Forum::route(self::ROUTE, $topLevelCategory), ['force' => true]);

        $this->assertEquals(0, Category::count());
        $this->assertEquals(0, Thread::count());
        $this->assertEquals(0, Post::count());
    }

    private function seedCategories(): Category
    {
        $topLevelCategory = $this->categoryFactory->createOne();
        $secondLevelCategory = $this->categoryFactory->createOne();
        $topLevelCategory->appendNode($secondLevelCategory);

        $topLevelThread = $this->threadFactory->createOne([
            'author_id' => $this->user->getKey(),
            'category_id' => $topLevelCategory->id,
        ]);
        $this->postFactory->createOne([
            'author_id' => $this->user->getKey(),
            'thread_id' => $topLevelThread->id,
        ]);

        $secondLevelThread = $this->threadFactory->createOne([
            'author_id' => $this->user->getKey(),
            'category_id' => $secondLevelCategory->id,
        ]);
        $this->postFactory->createOne([
            'author_id' => $this->user->getKey(),
            'thread_id' => $secondLevelThread->id,
        ]);

        return $topLevelCategory;
    }
}
