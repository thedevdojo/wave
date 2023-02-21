<?php

namespace TeamTeaTime\Forum\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use TeamTeaTime\Forum\Database\Factories\CategoryFactory;
use TeamTeaTime\Forum\Database\Factories\PostFactory;
use TeamTeaTime\Forum\Database\Factories\ThreadFactory;
use TeamTeaTime\Forum\Models\Category;

class ForumSeeder extends Seeder
{
    const THREAD_COUNT = 2;
    const POSTS_PER_THREAD = 5;

    public function run()
    {
        $userModel = config('forum.integration.user_model');
        $userId = DB::table((new $userModel)->getTable())->insertGetId([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('secret'),
        ]);

        $firstCategory = $this->createPopulatedCategory($userId);
        $secondCategory = CategoryFactory::new()->createOne();

        $subcategory = $this->createPopulatedCategory($userId);
        $firstCategory->appendNode($subcategory);
    }

    private function createPopulatedCategory(int $userId)
    {
        $category = CategoryFactory::new()
            ->state(function (array $attributes) {
                return [
                    'is_private' => 0,
                    'thread_count' => self::THREAD_COUNT,
                    'post_count' => self::THREAD_COUNT * self::POSTS_PER_THREAD,
                ];
            })
            ->createOne();

        $this->createThreadsInCategory($category, $userId);

        return $category;
    }

    private function createThreadsInCategory(Category $category, int $userId)
    {
        $threads = ThreadFactory::new()
            ->count(self::THREAD_COUNT)
            ->state(function (array $attributes) use ($category, $userId) {
                return [
                    'category_id' => $category->id,
                    'author_id' => $userId,
                    'reply_count' => self::POSTS_PER_THREAD - 1,
                ];
            })
            ->create();

        foreach ($threads as $thread) {
            $postFactory = PostFactory::new();
            for ($i = 1; $i <= self::POSTS_PER_THREAD; $i++) {
                $postFactory->state(function (array $attributes) use ($thread, $userId, $i) {
                    return [
                        'thread_id' => $thread->id,
                        'author_id' => $userId,
                        'sequence' => $i,
                    ];
                })->create();
            }

            $thread->first_post_id = $thread->posts()->orderBy('created_at', 'desc')->first()->id;
            $thread->last_post_id = $thread->getLastPost()->id;
            $thread->save();
        }

        $category->newest_thread_id = $category->getNewestThreadId();
        $category->latest_active_thread_id = $category->getLatestActiveThreadId();
        $category->save();
    }
}
