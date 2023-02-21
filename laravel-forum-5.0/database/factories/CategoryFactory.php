<?php

namespace TeamTeaTime\Forum\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TeamTeaTime\Forum\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'accepts_threads' => 1,
            'thread_count' => 0,
            'post_count' => 0,
            'is_private' => 0,
            'color' => '#007BFF',
        ];
    }
}
