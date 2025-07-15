<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class Plan extends Model
{
    protected $guarded = [];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all active plans with caching
     */
    public static function getActivePlans()
    {
        return Cache::remember('wave_active_plans', 1800, function () {
            return self::where('active', 1)->with('role')->get();
        });
    }

    /**
     * Get plan by name with caching
     */
    public static function getByName($name)
    {
        return Cache::remember("wave_plan_{$name}", 1800, function () use ($name) {
            return self::where('name', $name)->with('role')->first();
        });
    }

    /**
     * Clear plan cache
     */
    public static function clearCache()
    {
        Cache::forget('wave_active_plans');
        $plans = self::pluck('name');
        foreach ($plans as $planName) {
            Cache::forget("wave_plan_{$planName}");
        }
    }
}
