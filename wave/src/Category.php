<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    protected $guarded = [];

    public function posts(): HasMany
    {
        return $this->hasMany('Wave\Post');
    }

    /**
     * Get all categories with caching
     */
    public static function getAllCached()
    {
        return Cache::remember('wave_all_categories', 3600, function () {
            return self::all();
        });
    }

    /**
     * Clear categories cache
     */
    public static function clearCache()
    {
        Cache::forget('wave_all_categories');
    }
}
