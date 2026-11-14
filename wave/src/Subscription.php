<?php

namespace Wave;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'billable_type',
        'billable_id',
        'plan_id',
        'vendor_slug',
        'vendor_product_id',
        'vendor_transaction_id',
        'vendor_customer_id',
        'vendor_subscription_id',
        'cycle',
        'status',
        'seats',
        'trial_ends_at',
        'ends_at',
        'last_payment_at',
        'next_payment_at',
        'cancel_url',
        'update_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cancelled_at' => 'datetime',
            'last_payment_at' => 'datetime',
            'next_payment_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * The user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('wave.user_model', User::class), 'billable_id');
    }

    public function cancel()
    {
        DB::transaction(function () {
            // Serialize cancellations for this account before deciding which access remains.
            $user = $this->user()->lockForUpdate()->firstOrFail();
            $this->setRelation('user', $user);
            $this->status = 'cancelled';
            $this->save();

            $remaining = $user->latestSubscription();
            $defaultRole = config('wave.default_user_role', 'registered');
            $billingRoleIds = Plan::pluck('role_id');

            // Billing owns plan roles and the default role; retain separately assigned roles.
            $roles = $user->roles()->get()->reject(
                fn ($role) => $billingRoleIds->contains($role->id) || $role->name === $defaultRole
            )->all();
            $roles[] = $remaining ? $remaining->plan->role : $defaultRole;
            $user->syncRoles($roles);
        });

        $this->user->clearUserCache();
    }

    /**
     * The plan that belongs to the subscription.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
