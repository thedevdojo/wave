<?php

namespace Wave\Actions\Accounts;

use App\Models\User;
use Throwable;
use Wave\Actions\Billing\CancelSubscription;

class DeleteUser
{
    /** Cancel billing before the admin's existing soft-delete operation. */
    public function __invoke(User $user): bool
    {
        $subscriptions = $user->subscriptions()->where('status', 'active')->get();

        try {
            foreach ($subscriptions as $subscription) {
                if (! app(CancelSubscription::class)($subscription)) {
                    return false;
                }
            }

            return (bool) $user->delete();
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }
    }
}
