<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InspirationTag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'is_trending',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_trending' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the inspirations associated with the tag.
     */
    public function inspirations(): BelongsToMany
    {
        return $this->belongsToMany(Inspiration::class, 'inspiration_tag', 'tag_id', 'inspiration_id')
            ->withTimestamps();
    }

    /**
     * Get the users interested in this tag.
     */
    public function interestedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_interests', 'tag_id', 'user_id')
            ->withTimestamps();
    }
} 