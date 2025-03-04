<?php

namespace App\Models;

use Wave\Post as WavePost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends WavePost
{
    use HasFactory;
    
    public $guarded = [];
    
    protected $fillable = [
        'author_id',
        'content',
        'topic',
        'tone',
        'has_emoji',
        'has_hashtags',
        'is_longform',
        'posted_to_x',
        'x_post_id'
    ];
    
    protected $casts = [
        'has_emoji' => 'boolean',
        'has_hashtags' => 'boolean',
        'is_longform' => 'boolean',
        'posted_to_x' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the post.
     * This overrides the parent class method to use the same relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    
    /**
     * Scope a query to only include posts for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('author_id', $userId);
    }
    
    /**
     * Mark post as posted to X
     */
    public function markAsPostedToX($xPostId)
    {
        $this->posted_to_x = true;
        $this->x_post_id = $xPostId;
        $this->save();
        
        return $this;
    }
    
    /**
     * Get hashtags from content
     */
    public function getHashtags()
    {
        preg_match_all('/#(\w+)/', $this->content, $matches);
        return $matches[1] ?? [];
    }
}
