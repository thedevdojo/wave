<?php

namespace Database\Seeders;

use App\Models\Inspiration;
use App\Models\InspirationTag;
use Illuminate\Database\Seeder;

class InspirationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inspirations = [
            [
                'title' => 'Marketing Strategy Tips',
                'content' => "1. Define your target audience clearly\n2. Set measurable marketing goals\n3. Analyze your competitors\n4. Create a unique value proposition\n5. Choose the right marketing channels\n6. Develop a content calendar\n7. Implement, measure, and adjust",
                'type' => 'post',
                'tone' => 'professional',
                'is_featured' => true,
                'tags' => ['Marketing', 'Strategy', 'Business'],
            ],
            [
                'title' => 'Productivity Hacks',
                'content' => "Struggling with productivity? Try these proven techniques:\n\n• Time blocking: Schedule specific tasks for specific times\n• Pomodoro technique: Work in 25-minute focused bursts\n• Two-minute rule: If it takes less than 2 minutes, do it now\n• Batch similar tasks together\n• Turn off notifications during deep work\n\nWhich one will you try first?",
                'type' => 'post',
                'tone' => 'casual',
                'is_featured' => true,
                'tags' => ['Productivity', 'Personal Development', 'Management'],
            ],
            [
                'title' => 'AI Trends',
                'content' => "The top AI trends to watch this year:\n\n1. Generative AI going mainstream\n2. AI ethics and governance frameworks\n3. AI-powered cybersecurity solutions\n4. Edge AI reducing cloud dependency\n5. AI in healthcare diagnostics\n6. Multimodal AI systems\n7. AI for climate change solutions\n\nWhich trend do you think will have the biggest impact?",
                'type' => 'post',
                'tone' => 'informative',
                'is_featured' => false,
                'tags' => ['Artificial Intelligence', 'Technology', 'Innovation'],
            ],
            [
                'title' => 'Remote Work Tips',
                'content' => "After 3 years of remote work, here's what I've learned:\n\n• Create a dedicated workspace\n• Stick to a consistent schedule\n• Take regular breaks (seriously!)\n• Overcommunicate with your team\n• Use video calls for important discussions\n• Set boundaries between work and personal life\n• Invest in good equipment\n\nWhat's your best remote work tip?",
                'type' => 'post',
                'tone' => 'conversational',
                'is_featured' => false,
                'tags' => ['Remote Work', 'Productivity', 'Management'],
            ],
            [
                'title' => 'Content Marketing Strategy',
                'content' => "Building a content marketing strategy that works:\n\n1. Identify your audience's pain points\n2. Map content to each stage of the buyer's journey\n3. Focus on quality over quantity\n4. Repurpose successful content across channels\n5. Incorporate SEO best practices\n6. Measure performance with the right metrics\n7. Continuously optimize based on data\n\nWhat's your biggest content marketing challenge?",
                'type' => 'post',
                'tone' => 'professional',
                'is_featured' => true,
                'tags' => ['Content Marketing', 'Marketing', 'Strategy'],
            ],
        ];

        foreach ($inspirations as $inspirationData) {
            $tagNames = $inspirationData['tags'];
            unset($inspirationData['tags']);
            
            $inspiration = Inspiration::create($inspirationData);
            
            // Attach tags
            foreach ($tagNames as $tagName) {
                $tag = InspirationTag::firstOrCreate(
                    ['name' => $tagName],
                    [
                        'slug' => \Illuminate\Support\Str::slug($tagName),
                        'category' => $this->getCategoryForTag($tagName),
                        'description' => "Content related to {$tagName}",
                    ]
                );
                
                $inspiration->tags()->attach($tag->id);
            }
        }
    }
    
    /**
     * Get a default category for a tag if it doesn't exist yet.
     */
    private function getCategoryForTag(string $tagName): string
    {
        $tagCategories = [
            'Marketing' => 'Business',
            'Strategy' => 'Business',
            'Business' => 'Business',
            'Productivity' => 'Business',
            'Management' => 'Business',
            'Personal Development' => 'Lifestyle',
            'Artificial Intelligence' => 'Technology',
            'Technology' => 'Technology',
            'Innovation' => 'Technology',
            'Remote Work' => 'Business',
            'Content Marketing' => 'Business',
        ];
        
        return $tagCategories[$tagName] ?? 'Other';
    }
} 