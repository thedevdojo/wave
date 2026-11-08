<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Wave\Category;
use Wave\Post;

/**
 * @extends Factory<Post>
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
        return [
            'author_id' => User::factory(),
            'category_id' => Category::factory(),
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
