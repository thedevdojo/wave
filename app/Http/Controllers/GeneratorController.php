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

            // Get current workspace ID from session
            $workspaceId = session('current_workspace_id');
            
            // Add workspace_id to validated data
            if ($workspaceId !== null) {
                // Regular workspace handling
                $validatedData['workspace_id'] = $workspaceId;
                
                // Add workspace-specific custom instructions if available
                $customInstructions = \App\Models\UserSetting::getForWorkspace(
                    $workspaceId, 
                    'custom_instructions', 
                    null
                );
                
                if ($customInstructions) {
                    $validatedData['custom_instructions'] = $customInstructions;
                }
                
                // Add workspace-specific fine-tuning settings if available
                $fineTuningSettings = \App\Models\UserSetting::getForWorkspace(
                    $workspaceId, 
                    'fine_tuning_settings', 
                    null
                );
                
                if ($fineTuningSettings) {
                    $validatedData['fine_tuning'] = $fineTuningSettings;
                }
            } else {
                // Default workspace (NULL workspace_id)
                // Use user's global settings
                $customInstructions = $user->getSetting('custom_instructions', null);
                if ($customInstructions) {
                    $validatedData['custom_instructions'] = $customInstructions;
                }
                
                $fineTuningSettings = $user->getSetting('fine_tuning_settings', null);
                if ($fineTuningSettings) {
                    $validatedData['fine_tuning'] = $fineTuningSettings;
                }
            }

            // Generate the post
            $generatedPost = $this->openAIService->generatePost(
                $validatedData, 
                $validatedData['custom_instructions'] ?? ''
            );

            if ($generatedPost) {
                // Deduct credit only if post generation was successful
                $user->deductPostCredit();
                
                // Save the generated post
                $post = new GeneratedPost();
                $post->user_id = $user->id;
                $post->content = $generatedPost;
                $post->topic = $validatedData['topic'];
                $post->tone = $validatedData['tone'];
                $post->has_emoji = $validatedData['emoji'] ?? false;
                $post->has_hashtags = $validatedData['hashtags'] ?? false;
                $post->is_longform = $validatedData['longform'] ?? false;
                
                // Add workspace_id if provided
                if (isset($validatedData['workspace_id'])) {
                    $post->workspace_id = $validatedData['workspace_id'];
                }
                
                // Save settings used for generation
                $post->settings = [
                    'custom_instructions' => $validatedData['custom_instructions'] ?? null,
                    'fine_tuning' => $validatedData['fine_tuning'] ?? null
                ];
                
                $post->save();
                
                Log::info('Post generated successfully', [
                    'user_id' => $user->id,
                    'topic' => $validatedData['topic'],
                    'workspace_id' => $workspaceId,
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
                    'workspace_id' => $workspaceId,
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