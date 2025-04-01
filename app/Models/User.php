<?php

namespace App\Models;

use Illuminate\Support\Str;
use Wave\User as WaveUser;
use Illuminate\Notifications\Notifiable;
use Wave\Traits\HasProfileKeyValues;

class User extends WaveUser
{
    use Notifiable, HasProfileKeyValues;

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
        'post_credits',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the settings for the user
     */
    public function settings()
    {
        return $this->hasMany(UserSetting::class);
    }
    
    /**
     * Get all workspaces the user belongs to as a member
     */
    public function memberWorkspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }
    
    /**
     * Get workspaces owned by the user
     */
    public function ownedWorkspaces()
    {
        return $this->hasMany(Workspace::class, 'user_id');
    }
    
    /**
     * Check if user has agency plan
     */
    public function hasAgencyPlan()
    {
        return $this->subscription && $this->subscription->plan->name === 'Agency';
    }
    
    /**
     * Get a workspace-specific setting
     *
     * @param int $workspaceId
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getWorkspaceSetting($workspaceId, $key, $default = null)
    {
        return UserSetting::getForWorkspace($workspaceId, $key, $default);
    }
    
    /**
     * Set a workspace-specific setting
     *
     * @param int $workspaceId
     * @param string $key
     * @param mixed $value
     * @return UserSetting
     */
    public function setWorkspaceSetting($workspaceId, $key, $value)
    {
        return UserSetting::setForWorkspace($workspaceId, $key, $value);
    }
    
    /**
     * Get a user setting by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSetting($key, $default = null)
    {
        return UserSetting::getForUser($this->id, $key, $default);
    }
    
    /**
     * Set a user setting
     *
     * @param string $key
     * @param mixed $value
     * @return UserSetting
     */
    public function setSetting($key, $value)
    {
        return UserSetting::setForUser($this->id, $key, $value);
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
                    $username = Str::slug($user->name, '') . $i;
                    $i++;
                }
                $user->username = $username;
            }
        });

        // Listen for the created event of the model
        static::created(function ($user) {
            // Remove all roles
            $user->syncRoles([]);
            // Assign the default role
            $user->assignRole( config('wave.default_user_role', 'registered') );
        });
    }

    public function deductPostCredit()
    {
        if ($this->post_credits > 0) {
            $this->decrement('post_credits');
            return true;
        }
        return false;
    }

    public function addPostCredits($amount)
    {
        $this->increment('post_credits', $amount);
    }

    /**
     * Get the interests associated with the user.
     */
    public function interests()
    {
        return $this->belongsToMany(InspirationTag::class, 'user_interests', 'user_id', 'tag_id')
            ->withTimestamps();
    }
}
