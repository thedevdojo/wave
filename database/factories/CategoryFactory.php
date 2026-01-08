<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wave\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Wave\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => null,
            'order' => fake()->numberBetween(1, 100),
            'name' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(),
        ];
    }
}
