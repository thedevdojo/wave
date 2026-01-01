<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity for a user
     */
    public static function log(string $action, ?string $description = null, ?array $metadata = null): ?self
    {
        // Check if activity logging is enabled
        if (! config('activity.enabled', true)) {
            return null;
        }

        // Skip if no authenticated user
        if (! auth()->check()) {
            return null;
        }

        $data = [
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->header('CF-Connecting-IP') ?? request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ];

        // If queueing is enabled, dispatch to queue
        if (config('activity.queue', false)) {
            dispatch(function () use ($data) {
                static::create($data);
            })->onConnection(config('activity.queue_connection', 'sync'));

            return null;
        }

        return static::create($data);
    }
}
