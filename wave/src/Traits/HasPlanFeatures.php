<?php

namespace Wave\Traits;

use Illuminate\Support\Facades\Cache;
use Wave\Plan;

trait HasPlanFeatures
{
    /**
     * In-memory cache for usage counts within a single request.
     */
    protected array $featureUsageCache = [];

    /**
     * Get the limit for a specific feature based on the user's plan.
     * Returns null if unlimited, int if limited.
     */
    public function featureLimit(string $feature): ?int
    {
        // Admin bypass
        if (config('limits.admin_bypass', true) && $this->hasRole('admin')) {
            return null;
        }

        $subscription = $this->latestSubscription();

        if (! $subscription) {
            return $this->getDefaultLimit($feature);
        }

        $plan = Plan::find($subscription->plan_id);

        if (! $plan) {
            return $this->getDefaultLimit($feature);
        }

        $limits = $plan->limits ?? [];

        // Feature not defined in plan limits = unlimited
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
     * Get the current usage count for a feature.
     */
    public function featureUsage(string $feature): int
    {
        // Check in-memory cache first
        if (isset($this->featureUsageCache[$feature])) {
            return $this->featureUsageCache[$feature];
        }

        $config = config("limits.features.{$feature}");

        if (! $config) {
            return 0;
        }

        $modelClass = $config['model'] ?? null;
        $column = $config['column'] ?? 'user_id';

        if (! $modelClass || ! class_exists($modelClass)) {
            return 0;
        }

        $count = $modelClass::where($column, $this->id)->count();

        // Cache for this request
        $this->featureUsageCache[$feature] = $count;

        return $count;
    }

    /**
     * Check if user can use more of a feature (create new items).
     */
    public function canUseFeature(string $feature, int $amount = 1): bool
    {
        $limit = $this->featureLimit($feature);

        // Null means unlimited
        if ($limit === null) {
            return true;
        }

        // 0 means feature is disabled
        if ($limit === 0) {
            return false;
        }

        return ($this->featureUsage($feature) + $amount) <= $limit;
    }

    /**
     * Get remaining quota for a feature.
     * Returns null if unlimited.
     */
    public function featureRemaining(string $feature): ?int
    {
        $limit = $this->featureLimit($feature);

        if ($limit === null) {
            return null;
        }

        $remaining = $limit - $this->featureUsage($feature);

        return max(0, $remaining);
    }

    /**
     * Check if the feature limit has been reached.
     */
    public function featureLimitReached(string $feature): bool
    {
        return ! $this->canUseFeature($feature);
    }

    /**
     * Get usage as a percentage (0-100).
     * Returns null if unlimited.
     */
    public function featureUsagePercent(string $feature): ?float
    {
        $limit = $this->featureLimit($feature);

        if ($limit === null || $limit === 0) {
            return null;
        }

        $usage = $this->featureUsage($feature);

        return min(100, round(($usage / $limit) * 100, 1));
    }

    /**
     * Check if user is approaching the feature limit.
     * Default threshold is 80% (0.8).
     */
    public function featureNearLimit(string $feature, float $threshold = 0.8): bool
    {
        $limit = $this->featureLimit($feature);

        if ($limit === null || $limit === 0) {
            return false;
        }

        $usage = $this->featureUsage($feature);

        return ($usage / $limit) >= $threshold;
    }

    /**
     * Get all feature limits for the user's current plan.
     */
    public function allFeatureLimits(): array
    {
        $subscription = $this->latestSubscription();

        if (! $subscription) {
            return config('limits.defaults', []);
        }

        $plan = Plan::find($subscription->plan_id);

        if (! $plan) {
            return config('limits.defaults', []);
        }

        return $plan->limits ?? [];
    }

    /**
     * Clear the in-memory feature usage cache.
     */
    public function clearFeatureUsageCache(): void
    {
        $this->featureUsageCache = [];
    }

    /**
     * Get default limit for users without a plan.
     */
    protected function getDefaultLimit(string $feature): ?int
    {
        $defaults = config('limits.defaults', []);

        if (! array_key_exists($feature, $defaults)) {
            return null;
        }

        $limit = $defaults[$feature];

        if ($limit === -1) {
            return null;
        }

        return (int) $limit;
    }
}
