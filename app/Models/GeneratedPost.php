<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedPost extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'content',
        'topic',
        'tone',
        'has_emoji',
        'has_hashtags',
        'is_longform',
        'posted_to_x',
        'x_post_id',
        'settings',
    ];
    
    protected $casts = [
        'has_emoji' => 'boolean',
        'has_hashtags' => 'boolean',
        'is_longform' => 'boolean',
        'posted_to_x' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'settings' => 'array',
    ];
    
    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include posts for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
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
