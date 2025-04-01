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

    public function generatePost($data, $customInstructions = '')
    {
        $prompt = $this->buildPrompt($data, $customInstructions);

        // Get fine-tuning settings or use defaults
        $fineTuning = $data['fine_tuning'] ?? [];
        $temperature = $fineTuning['temperature'] ?? 0.7;
        $topP = $fineTuning['top_p'] ?? 1.0;
        $frequencyPenalty = $fineTuning['frequency_penalty'] ?? 0.0;
        $presencePenalty = $fineTuning['presence_penalty'] ?? 0.0;
        $maxTokens = $data['longform'] ? ($fineTuning['max_tokens'] ?? 280) : ($fineTuning['max_tokens'] ?? 140);

        // Log the generation request for debugging
        logger()->debug('Generating post with settings', [
            'topic' => $data['topic'],
            'tone' => $data['tone'],
            'workspace_id' => $data['workspace_id'] ?? null,
            'temperature' => $temperature,
            'top_p' => $topP,
            'frequency_penalty' => $frequencyPenalty,
            'presence_penalty' => $presencePenalty,
            'max_tokens' => $maxTokens
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional social media content creator who specializes in creating engaging posts for X (formerly Twitter).'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
            'top_p' => $topP,
            'frequency_penalty' => $frequencyPenalty,
            'presence_penalty' => $presencePenalty,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        throw new \Exception('Failed to generate post: ' . $response->body());
    }

    protected function buildPrompt($data, $customInstructions = '')
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

        if ($customInstructions) {
            $prompt .= " " . $customInstructions;
        }

        return $prompt;
    }
}