<?php

namespace Wave;

use Wave\Plan;
use Carbon\Carbon;
use Wave\Changelog;
use Wave\Subscription;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Devdojo\Auth\Models\User as AuthUser;
use Lab404\Impersonate\Models\Impersonate;

class User extends AuthUser implements JWTSubject, HasAvatar, FilamentUser
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
        'avatar',
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

    

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'billable_id')->where('billable_type', 'user');
    }

    public function subscriber()
    {
        return $this->subscriptions()->where('status', 'active')->exists();
    }

    public function subscribedToPlan($planSlug)
    {
        $plan = Plan::where('name', $planSlug)->first();
        if (!$plan) {
            return false;
        }
        return $this->subscriptions()->where('plan_id', $plan->id)->where('status', 'active')->exists();
    }

    public function plan(){
        $latest_subscription = $this->latestSubscription();
        return Plan::find($latest_subscription->plan_id);
    }

    public function planInterval(){
        $latest_subscription = $this->latestSubscription();
        return ($latest_subscription->cycle == 'month') ? 'Monthly' : 'Yearly'; 
    }

    public function latestSubscription()
    {
        return $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first();
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'billable_id')->where('status', 'active')->orderBy('created_at', 'desc');
    }

    public function switchPlans(Plan $plan){
        $this->syncRoles([]);
        $this->assignRole( $plan->role->name );
    }

    public function invoices(){
        $user_invoices = [];

        if(is_null($this->subscription)){
            return null;
        }

        if(config('wave.billing_provider') == 'stripe'){
            $stripe = new \Stripe\StripeClient(config('wave.stripe.secret_key'));
            $subscriptions = $this->subscriptions()->get();        
            foreach($subscriptions as $subscription){
                $invoices = $stripe->invoices->all([ 'customer' => $subscription->vendor_customer_id, 'limit' => 100 ]);

                foreach($invoices as $invoice){
                    array_push($user_invoices, (object)[
                        'id' => $invoice->id,
                        'created' => \Carbon\Carbon::parse($invoice->created)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
                        'total' => number_format(($invoice->total /100), 2, '.', ' '),
                        'download' => $invoice->invoice_pdf
                    ]);
                }
            }
        } else { 
            $paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
            $response = Http::withToken(config('wave.paddle.api_key'))->get($paddle_url . '/transactions', [
                'subscription_id' => $this->subscription->vendor_subscription_id
            ]);
            $responseJson = json_decode($response->body());
            foreach($responseJson->data as $invoice){
                array_push($user_invoices, (object)[
                    'id' => $invoice->id,
                    'created' => \Carbon\Carbon::parse($invoice->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
                    'total' => number_format(($invoice->details->totals->subtotal /100), 2, '.', ' '),
                    'download' => '/settings/invoices/' . $invoice->id
                ]);
            }
        }

        return $user_invoices;
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

    public function link(){
        return url('/profile/' . $this->username);
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

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar();
    }

    public function profile($key)
    {
        $keyValue = $this->profileKeyValue($key);
        return isset($keyValue->value) ? $keyValue->value : '';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin' && auth()->user()->hasRole('admin')) {
            return true;
        }

        return false;
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
