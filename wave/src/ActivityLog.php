<?php

namespace Wave;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Wave\Contracts\ActivityLoggerInterface;
use Wave\Jobs\CreateActivityLog;

/**
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string|null $description
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array<string, mixed>|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static ActivityLog create(array $attributes = [])
 * @method static ActivityLog|null find(int $id)
 * @method static Collection<int, ActivityLog> where(string $column, mixed $operator = null, mixed $value = null)
 */
class ActivityLog extends Model implements ActivityLoggerInterface
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
        return $this->belongsTo(config('wave.user_model'));
    }

    /**
     * Log an activity for the current authenticated user.
     *
     * @param string $action The action identifier (e.g., 'password_changed', 'login')
     * @param string|null $description Human-readable description
     * @param array<string, mixed>|null $metadata Additional context data
     * @return self|null Returns the created log or null if logging is disabled/queued
     */
    public static function log(string $action, ?string $description = null, ?array $metadata = null): ?static
    {
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
            CreateActivityLog::dispatch($data)
                ->onConnection(config('activity.queue_connection', 'database'));

            return null;
        }

        return static::create($data);
    }
}
