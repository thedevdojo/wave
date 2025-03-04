<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    public function generatePost($data)
    {
        $prompt = $this->buildPrompt($data);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional social media content creator who specializes in creating engaging posts for X (formerly Twitter).'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => $data['longform'] ? 280 : 140,
            'temperature' => 0.7,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        throw new \Exception('Failed to generate post: ' . $response->body());
    }

    protected function buildPrompt($data)
    {
        $prompt = "Generate a {$data['tone']} post for X (formerly Twitter) about {$data['topic']}";
        
        if ($data['longform']) {
            $prompt .= " This should be a longer post, but still within X's character limits.";
        } else {
            $prompt .= " Keep it concise and impactful.";
        }
        
        if ($data['emoji']) {
            $prompt .= " Include appropriate emojis that enhance the message.";
        }
        
        if ($data['hashtags']) {
            $prompt .= " Add 2-3 relevant hashtags that would help with discoverability.";
        }

        return $prompt;
    }
}