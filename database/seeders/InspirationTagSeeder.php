<?php

namespace Database\Seeders;

use App\Models\InspirationTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InspirationTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Business' => [
                'Marketing',
                'Entrepreneurship',
                'Leadership',
                'Sales',
                'Productivity',
                'Management',
                'Startups',
                'E-commerce',
            ],
            'Technology' => [
                'Web Development',
                'Mobile Apps',
                'Artificial Intelligence',
                'Blockchain',
                'Cybersecurity',
                'Cloud Computing',
                'Data Science',
                'IoT',
            ],
            'Lifestyle' => [
                'Health',
                'Fitness',
                'Travel',
                'Food',
                'Fashion',
                'Personal Development',
                'Mindfulness',
                'Relationships',
            ],
            'Education' => [
                'Online Learning',
                'Career Development',
                'Skills Training',
                'Higher Education',
                'Teaching',
                'Student Life',
                'Educational Technology',
                'Research',
            ],
            'Creative' => [
                'Design',
                'Photography',
                'Writing',
                'Music',
                'Art',
                'Film',
                'Animation',
                'Crafts',
            ],
        ];

        $order = 1;
        foreach ($categories as $category => $tags) {
            foreach ($tags as $tag) {
                InspirationTag::create([
                    'name' => $tag,
                    'slug' => Str::slug($tag),
                    'category' => $category,
                    'description' => "Content related to {$tag} in the {$category} category",
                    'is_trending' => rand(0, 1) === 1,
                    'display_order' => $order++,
                ]);
            }
        }
    }
} 