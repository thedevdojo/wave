<?php

namespace Wave;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lab404\Impersonate\Models\Impersonate;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Wave\Changelog;
use Wave\PaddleSubscription;
use Wave\Plan;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, Impersonate, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role_id',
        'verification_code',
        'verified',
        'trial_ends_at',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Listen for the creating event of the model
        static::creating(function ($user) {
            // Check if the username attribute is empty
            if (empty($user->username)) {
                // Use the email to generate a slugified username
                // For example, 'john.doe@example.com' becomes 'john-doe'
                $user->username = Str::slug(explode('@', $user->email)[0], '-');
            }
        });
    }

    public function keyValues()
    {
        return $this->morphMany('Wave\KeyValue', 'keyvalue');
    }

    public function keyValue($key)
    {
        return $this->morphMany('Wave\KeyValue', 'keyvalue')->where('key', '=', $key)->first();
    }

    public function profile($key)
    {
        $keyValue = $this->keyValue($key);
        return isset($keyValue->value) ? $keyValue->value : '';
    }

    public function onTrial()
    {
        if (is_null($this->trial_ends_at)) {
            return false;
        }
        if ($this->subscriber()) {
            return false;
        }
        return true;
    }

    public function subscribed($plan)
    {

        $plan = Plan::where('slug', $plan)->first();

        // if the user is an admin they automatically have access to the default plan
        if (isset($plan->default) && $plan->default && $this->hasRole('admin')) return true;

        if (isset($plan->slug) && $this->hasRole($plan->slug)) {
            return true;
        }

        return false;
    }

    public function subscriber()
    {

        if ($this->hasRole('admin')) return true;

        $roles = $this->roles->pluck('id')->push($this->role_id)->unique();
        $plans = Plan::whereIn('role_id', $roles)->count();

        // If the user has a role that belongs to a plan
        if ($plans) {
            return true;
        }

        return false;
    }

    public function subscription()
    {
        return $this->hasOne(PaddleSubscription::class);
    }


    public function latestSubscription()
    {
        return $this->hasOne(PaddleSubscription::class)->latest();
    }


    /**
     * @return bool
     */
    public function canImpersonate()
    {
        // If user is admin they can impersonate
        return $this->hasRole('admin');
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        // return if the user has a role of admin
        return $this->hasRole('admin');
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        // Any user that is not an admin can be impersonated
        return !$this->hasRole('admin');
    }

    public function hasChangelogNotifications()
    {
        // Get the latest Changelog
        $latest_changelog = Changelog::orderBy('created_at', 'DESC')->first();

        if (!$latest_changelog) return false;
        return !$this->changelogs->contains($latest_changelog->id);
    }

    public function changelogs()
    {
        return $this->belongsToMany('Wave\Changelog');
    }

    public function createApiKey($name)
    {
        return ApiKey::create(['user_id' => $this->id, 'name' => $name, 'key' => Str::random(60)]);
    }

    public function apiKeys()
    {
        return $this->hasMany('Wave\ApiKey')->orderBy('created_at', 'DESC');
    }

    public function daysLeftOnTrial()
    {
        if ($this->trial_ends_at && $this->trial_ends_at >= now()) {
            $trial_ends = Carbon::parse($this->trial_ends_at)->addDay();
            return $trial_ends->diffInDays(now());
        }
        return 0;
    }

    public function avatar()
    {
        return Storage::url($this->avatar);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /*** PUT ALL THESE below into a trait */

    /**
     * Return default User Role.
     */
    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
    // }

    // /**
    //  * Return alternative User Roles.
    //  */
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    // }

    // /**
    //  * Return all User Roles, merging the default and alternative roles.
    //  */
    // public function roles_all()
    // {
    //     $this->loadRolesRelations();

    //     return collect([$this->role])->merge($this->roles);
    // }

    // /**
    //  * Check if User has a Role(s) associated.
    //  *
    //  * @param string|array $name The role(s) to check.
    //  *
    //  * @return bool
    //  */
    // public function hasRole($name)
    // {
    //     $roles = $this->roles_all()->pluck('name')->toArray();

    //     foreach ((is_array($name) ? $name : [$name]) as $role) {
    //         if (in_array($role, $roles)) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    // /**
    //  * Set default User Role.
    //  *
    //  * @param string $name The role name to associate.
    //  */
    // public function setRole($name)
    // {
    //     $role = Role::where('name', '=', $name)->first();

    //     if ($role) {
    //         $this->role()->associate($role);
    //         $this->save();
    //     }

    //     return $this;
    // }

    // public function hasPermission($name)
    // {
    //     $this->loadPermissionsRelations();

    //     $_permissions = $this->roles_all()
    //                           ->pluck('permissions')->flatten()
    //                           ->pluck('key')->unique()->toArray();

    //     return in_array($name, $_permissions);
    // }

    // public function hasPermissionOrFail($name)
    // {
    //     if (!$this->hasPermission($name)) {
    //         throw new UnauthorizedHttpException(null);
    //     }

    //     return true;
    // }

    // public function hasPermissionOrAbort($name, $statusCode = 403)
    // {
    //     if (!$this->hasPermission($name)) {
    //         return abort($statusCode);
    //     }

    //     return true;
    // }

    // private function loadRolesRelations()
    // {
    //     if (!$this->relationLoaded('role')) {
    //         $this->load('role');
    //     }

    //     if (!$this->relationLoaded('roles')) {
    //         $this->load('roles');
    //     }
    // }

    // private function loadPermissionsRelations()
    // {
    //     $this->loadRolesRelations();

    //     if ($this->role && !$this->role->relationLoaded('permissions')) {
    //         $this->role->load('permissions');
    //         $this->load('roles.permissions');
    //     }
    // }
}
