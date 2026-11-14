<?php

namespace Wave\Actions\Accounts;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;
use Wave\Actions\Billing\CancelSubscription;

class DeleteUser
{
    /** Cancel billing before the admin's existing soft-delete operation. */
    public function __invoke(User $user): bool
    {
        $subscriptions = $user->subscriptions()->where('status', 'active')->get();

        // Without a provider ID we cannot establish that billing has stopped.
        if ($subscriptions->contains(fn ($subscription) => blank($subscription->vendor_subscription_id))) {
            Log::warning('Admin user deletion blocked: active subscription has no provider ID.', ['user_id' => $user->id]);

            return false;
        }

        try {
            foreach ($subscriptions as $subscription) {
                if (! app(CancelSubscription::class)($subscription)) {
                    return false;
                }
            }

            // Retain the account if a new active subscription appeared during cancellation.
            if ($user->subscriptions()->where('status', 'active')->exists()) {
                return false;
            }

            return (bool) $user->delete();
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }
    }
}
