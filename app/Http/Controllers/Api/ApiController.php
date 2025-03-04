<?php

namespace App\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\GeneratedPost;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function posts(){
        return Post::all();
    }
    
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            'access_token' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Logout user (Revoke the token)
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     */
    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Get user's available credits
     */
    public function getCredits(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'credits' => $user->post_credits,
            'plan' => $user->role->name ?? 'Free'
        ]);
    }

    /**
     * Get user's post generation history
     */
    public function getPostHistory(Request $request)
    {
        $user = $request->user();
        $posts = GeneratedPost::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(20)
                    ->get();
        
        return response()->json([
            'posts' => $posts
        ]);
    }

    /**
     * Save a generated post
     */
    public function savePost(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'topic' => 'required|string',
        ]);

        $user = $request->user();
        
        $post = new GeneratedPost();
        $post->user_id = $user->id;
        $post->content = $request->content;
        $post->topic = $request->topic;
        $post->tone = $request->tone ?? 'casual';
        $post->has_emoji = $request->has('has_emoji') ? $request->has_emoji : false;
        $post->has_hashtags = $request->has('has_hashtags') ? $request->has_hashtags : false;
        $post->is_longform = $request->has('is_longform') ? $request->is_longform : false;
        $post->save();
        
        return response()->json([
            'success' => true,
            'post' => $post
        ]);
    }
}