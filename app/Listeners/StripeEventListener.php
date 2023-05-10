<?php

namespace App\Listeners;

use Wave\User;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Role;
use App\Notifications\NewUserPassword;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle received Stripe webhooks.
     *
     * @param  \Laravel\Cashier\Events\WebhookReceived  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        if ($event->payload['type'] === 'customer.subscription.deleted') {
            $user = User::where('stripe_id', $event->payload['data']['object']['customer'])->first();
            if (!$user) return false;

            $cancelledRole = Role::where('name', '=', 'cancelled')->first();
            $user->role_id = $cancelledRole->id;
            $user->save();
        }
    }
}
