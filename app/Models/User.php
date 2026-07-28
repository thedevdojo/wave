<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Wave\ActivityLog;
use Wave\Traits\HasProfileKeyValues;
use Wave\User as WaveUser;

class User extends WaveUser
{
    use HasFactory, HasProfileKeyValues, Notifiable, SoftDeletes;

    public $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar',
        'password',
        'role_id',
        'verification_code',
        'verified',
        'trial_ends_at',
        'organization_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'notification_preferences' => 'array',
            'social_links' => 'array',
            'privacy_settings' => 'array',
            'deletion_scheduled_at' => 'datetime',
        ];
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function ownedOrganization(): HasOne
    {
        return $this->hasOne(Organization::class, 'user_id');
    }

    public function isOrganizationAdmin(): bool
    {
        return $this->ownedOrganization()->exists();
    }

    public function isOrganizationMember(): bool
    {
        return $this->organization_id !== null && !$this->isOrganizationAdmin();
    }

    /**
     * Get the Nylas accounts associated with the user.
     */
    public function nylasAccounts(): HasMany
    {
        return $this->hasMany(NylasAccount::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Listen for the creating event of the model
        static::creating(function ($user) {
            // Check if the username attribute is empty
            if (empty($user->username)) {
                // Use the name to generate a slugified username
                $username = Str::slug($user->name, '');
                $i = 1;
                while (self::where('username', $username)->exists()) {
                    $username = Str::slug($user->name, '').$i;
                    $i++;
                }
                $user->username = $username;
            }
        });

        // Listen for the created event of the model
        static::created(function ($user) {
            // Remove all roles
            $user->syncRoles([]);

            // Assign the default role if it exists
            $defaultRole = config('wave.default_user_role', 'registered');
            if (\Spatie\Permission\Models\Role::where('name', $defaultRole)->where('guard_name', 'web')->exists()) {
                $user->assignRole($defaultRole);
            }
        });
    }
}
