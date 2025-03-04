<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Inspiration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'type',
        'tone',
        'is_featured',
        'is_active',
        'usage_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'usage_count' => 'integer',
    ];

    /**
     * Get the tags associated with the inspiration.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(InspirationTag::class, 'inspiration_tag', 'inspiration_id', 'tag_id')
            ->withTimestamps();
    }

    /**
     * Increment the usage count for this inspiration.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
} 