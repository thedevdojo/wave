<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wave\Category;
use Wave\Post;
use Wave\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Wave\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'author_id' => $user->id,
            'category_id' => $category->id,
            'title' => fake()->sentence(),
            'seo_title' => fake()->sentence(),
            'excerpt' => fake()->paragraph(),
            'body' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'image' => 'posts/'.fake()->uuid().'.jpg',
            'slug' => fake()->unique()->slug(),
            'meta_description' => fake()->text(160),
            'meta_keywords' => fake()->words(5, true),
            'status' => 'PUBLISHED',
            'featured' => fake()->boolean(),
        ];
    }
}
