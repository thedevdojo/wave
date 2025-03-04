<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Inspiration;
use App\Models\InspirationTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InspirationController extends Controller
{
    /**
     * Display the inspiration index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $userInterests = $user->interests()->pluck('name')->toArray();
        
        $inspirations = Inspiration::with('tags')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $featuredInspirations = Inspiration::with('tags')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $categories = InspirationTag::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category');
            
        // Get trending topics based on popular tags
        $trendingTopics = InspirationTag::where('is_trending', true)
            ->orderBy('display_order')
            ->take(5)
            ->get()
            ->map(function($tag) {
                return [
                    'title' => $tag->name,
                    'content' => $tag->description,
                    'source' => 'system',
                    'trending_score' => rand(50, 150),
                    'created_at' => now()->subHours(rand(1, 24)),
                ];
            });
            
        return view('dashboard.inspiration.index', compact(
            'inspirations', 
            'featuredInspirations', 
            'categories', 
            'userInterests',
            'trendingTopics'
        ));
    }
    
    /**
     * Display the inspiration detail page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inspiration = Inspiration::with('tags')->findOrFail($id);
        
        // Increment usage count
        $inspiration->incrementUsage();
        
        // Get related inspirations based on tags
        $tagIds = $inspiration->tags->pluck('id')->toArray();
        $relatedInspirations = Inspiration::with('tags')
            ->where('id', '!=', $inspiration->id)
            ->where('is_active', true)
            ->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('inspiration_tags.id', $tagIds);
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        return view('dashboard.inspiration.show', compact('inspiration', 'relatedInspirations'));
    }
    
    /**
     * Display the manage interests page.
     *
     * @return \Illuminate\Http\Response
     */
    public function interests()
    {
        $user = Auth::user();
        $allTags = InspirationTag::orderBy('category')->orderBy('name')->get();
        $userInterestIds = $user->interests()->pluck('inspiration_tags.id')->toArray();
        $userInterests = $user->interests()->pluck('name')->toArray();
        
        return view('dashboard.inspiration.interests', compact('allTags', 'userInterestIds', 'userInterests'));
    }
    
    /**
     * Update user interests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateInterests(Request $request)
    {
        $validated = $request->validate([
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'exists:inspiration_tags,id',
        ]);
        
        $user = Auth::user();
        $user->interests()->sync($request->input('tag_ids'));
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Interests updated successfully',
                'interests' => $user->interests()->get(),
            ]);
        }
        
        return redirect()->route('inspiration.interests')
            ->with('success', 'Your interests have been updated successfully.');
    }
    
    /**
     * Generate a post from an inspiration topic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generatePost(Request $request)
    {
        // Get the topic from the request
        $topic = $request->input('topic');
        
        // Redirect to the generator page with the topic
        return redirect()->route('generator')
            ->with('topic', $topic);
    }
}
