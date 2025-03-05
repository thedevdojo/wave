<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\GeneratedPost;

class GeneratorController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('dashboard.generator.index', [
            'credits' => $user->post_credits,
        ]);
    }

    public function generate(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'topic' => 'required|string|max:255',
                'tone' => 'required|string|in:casual,formal,humorous,professional',
                'longform' => 'boolean',
                'emoji' => 'boolean',
                'hashtags' => 'boolean',
            ]);

            // Determine if the request is coming from the API or web
            $user = $request->user('api') ?? Auth::user();

            if (!$user) {
                Log::warning('Unauthenticated post generation attempt', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            // Check if user has enough credits
            if ($user->post_credits <= 0) {
                Log::info('User attempted to generate post with insufficient credits', [
                    'user_id' => $user->id,
                    'credits' => $user->post_credits,
                ]);
                return response()->json([
                    'error' => 'Insufficient credits',
                    'credits' => $user->post_credits,
                    'message' => 'You need to purchase more credits to generate posts.'
                ], 403);
            }

            // Generate the post
            $generatedPost = $this->openAIService->generatePost($validatedData);

            if ($generatedPost) {
                // Deduct credit only if post generation was successful
                $user->deductPostCredit();
                
                Log::info('Post generated successfully', [
                    'user_id' => $user->id,
                    'topic' => $validatedData['topic'],
                    'remaining_credits' => $user->post_credits,
                ]);
                
                return response()->json([
                    'success' => true,
                    'post' => $generatedPost,
                    'remaining_credits' => $user->post_credits,
                ]);
            } else {
                Log::error('Post generation failed with empty response', [
                    'user_id' => $user->id,
                    'topic' => $validatedData['topic'],
                ]);
                
                return response()->json([
                    'error' => 'Post generation failed',
                    'message' => 'The AI service returned an empty response. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Post generation exception', [
                'user_id' => $request->user() ? $request->user()->id : null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'error' => 'Post generation failed',
                'message' => 'An error occurred while generating your post. Please try again later.'
            ], 500);
        }
    }

    public function history()
    {
        $posts = GeneratedPost::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('dashboard.generator.posts.index', compact('posts'));
    }
}