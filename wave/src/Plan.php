<?php

namespace Wave;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    ];

    protected static function booted(): void
    {
        // Also forget the old name so a renamed or deleted plan isn't served from cache.
        static::saved(fn (Plan $plan) => static::clearCache([$plan->getOriginal('name'), $plan->name]));
        static::deleted(fn (Plan $plan) => static::clearCache([$plan->name]));
    }

    /**
     * Features are a comma-separated string, which is what the themes and the admin form use.
     */
    protected function features(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => static::featuresToString($value),
            set: fn ($value) => static::featuresToString($value),
        );
    }

    /**
     * The plan's features as a list, without blank entries.
     */
    public function featureList(): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', $this->features)),
            fn (string $feature) => $feature !== ''
        ));
    }

    protected static function featuresToString(mixed $value): string
    {
        // Rows saved while features had an array cast hold JSON (an array or a quoted string).
        if (is_string($value) && (str_starts_with($value, '[') || str_starts_with($value, '"'))) {
            $decoded = json_decode($value, true);

            if (is_array($decoded) || is_string($decoded)) {
                $value = $decoded;
            }
        }

        if (is_array($value)) {
            $value = implode(',', array_map(fn ($feature) => trim((string) $feature), $value));
        }

        return (string) $value;
    }

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
    public static function clearCache(array $extraNames = [])
    {
        // Only clear cache if it's available
        if (app()->bound('cache')) {
            try {
                Cache::forget('wave_active_plans');
                $plans = self::pluck('name')->merge($extraNames)->filter()->unique();
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

        return static::normalizeLimit($limits[$feature]);
    }

    /**
     * Empty and -1 mean unlimited, 0 means disabled. Values saved from the admin form are strings.
     */
    public static function normalizeLimit(mixed $limit): ?int
    {
        if (is_string($limit)) {
            $limit = trim($limit);
        }

        if ($limit === null || $limit === '') {
            return null;
        }

        $limit = (int) $limit;

        return $limit === -1 ? null : $limit;
    }

    /**
     * Check if plan has a limit defined for a feature.
     */
    public function hasLimit(string $feature): bool
    {
        return array_key_exists($feature, $this->limits ?? []);
    }
}
