<?php

namespace Wave;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class Plan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'limits' => 'array',
        'features' => 'array',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all active plans with caching
     */
    public static function getActivePlans()
    {
        // Use cache if available, otherwise direct query
        if (app()->bound('cache')) {
            try {
                return Cache::remember('wave_active_plans', 1800, function () {
                    return self::where('active', 1)->orderBy('sort_order')->orderBy('id')->with('role')->get();
                });
            } catch (Exception $e) {
                // Fallback to direct query if cache fails
            }
        }

        return self::where('active', 1)->orderBy('sort_order')->orderBy('id')->with('role')->get();
    }

    /**
     * Get plan by name with caching
     */
    public static function getByName($name)
    {
        // Use cache if available, otherwise direct query
        if (app()->bound('cache')) {
            try {
                return Cache::remember("wave_plan_{$name}", 1800, function () use ($name) {
                    return self::where('name', $name)->with('role')->first();
                });
            } catch (Exception $e) {
                // Fallback to direct query if cache fails
            }
        }

        return self::where('name', $name)->with('role')->first();
    }

    /**
     * Clear plan cache
     */
    public static function clearCache()
    {
        // Only clear cache if it's available
        if (app()->bound('cache')) {
            try {
                Cache::forget('wave_active_plans');
                $plans = self::pluck('name');
                foreach ($plans as $planName) {
                    Cache::forget("wave_plan_{$planName}");
                }
            } catch (Exception $e) {
                // Silently handle cache clearing failures
            }
        }
    }

    /**
     * Get the limit for a specific feature.
     * Returns null if unlimited, int if limited.
     */
    public function getLimit(string $feature): ?int
    {
        $limits = $this->limits ?? [];

        if (! array_key_exists($feature, $limits)) {
            return null;
        }

        $limit = $limits[$feature];

        // -1 means explicitly unlimited
        if ($limit === -1) {
            return null;
        }

        return (int) $limit;
    }

    /**
     * Check if plan has a limit defined for a feature.
     */
    public function hasLimit(string $feature): bool
    {
        return array_key_exists($feature, $this->limits ?? []);
    }
}
