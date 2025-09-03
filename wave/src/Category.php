<?php

namespace Wave;

use Exception;
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
        // Use cache if available, otherwise direct query
        if (app()->bound('cache')) {
            try {
                return Cache::remember('wave_all_categories', 3600, function () {
                    return self::all();
                });
            } catch (Exception $e) {
                // Fallback to direct query if cache fails
            }
        }

        return self::all();
    }

    /**
     * Clear categories cache
     */
    public static function clearCache()
    {
        // Only clear cache if it's available
        if (app()->bound('cache')) {
            try {
                Cache::forget('wave_all_categories');
            } catch (Exception $e) {
                // Silently handle cache clearing failures
            }
        }
    }
}
