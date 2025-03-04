<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspiration;
use App\Models\InspirationTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InspirationController extends Controller
{
    /**
     * Get a list of inspirations, optionally filtered by tags.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Inspiration::query()->with('tags');
        
        // Filter by tag if provided
        if ($request->has('tag')) {
            $tagSlug = $request->input('tag');
            $tag = InspirationTag::where('slug', $tagSlug)->first();
            
            if ($tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('inspiration_tags.id', $tag->id);
                });
            }
        }
        
        // Filter by category if provided
        if ($request->has('category')) {
            $category = $request->input('category');
            $query->whereHas('tags', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }
        
        // Filter by type if provided
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }
        
        // Filter by tone if provided
        if ($request->has('tone')) {
            $query->where('tone', $request->input('tone'));
        }
        
        // Filter by featured
        if ($request->has('featured') && $request->input('featured') === 'true') {
            $query->where('is_featured', true);
        }
        
        // Get user interests if requested
        if ($request->has('user_interests') && $request->input('user_interests') === 'true') {
            $user = Auth::user();
            if ($user && $user->interests()->count() > 0) {
                $tagIds = $user->interests()->pluck('inspiration_tags.id')->toArray();
                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('inspiration_tags.id', $tagIds);
                });
            }
        }
        
        // Paginate results
        $perPage = $request->input('per_page', 10);
        $inspirations = $query->paginate($perPage);
        
        return response()->json($inspirations);
    }
    
    /**
     * Get a specific inspiration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inspiration = Inspiration::with('tags')->findOrFail($id);
        
        // Increment usage count
        $inspiration->incrementUsage();
        
        return response()->json($inspiration);
    }
    
    /**
     * Get all available categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        $categories = InspirationTag::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category');
            
        return response()->json($categories);
    }
    
    /**
     * Get all available tags, optionally filtered by category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function tags(Request $request)
    {
        $query = InspirationTag::query();
        
        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }
        
        $tags = $query->orderBy('display_order')->get();
        
        return response()->json($tags);
    }
    
    /**
     * Update user interests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateInterests(Request $request)
    {
        $request->validate([
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'exists:inspiration_tags,id',
        ]);
        
        $user = Auth::user();
        $user->interests()->sync($request->input('tag_ids'));
        
        return response()->json([
            'message' => 'Interests updated successfully',
            'interests' => $user->interests,
        ]);
    }
} 