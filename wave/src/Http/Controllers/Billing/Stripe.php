<?php

namespace Wave\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Stripe\StripeClient;

class Stripe extends Controller
{
    public function redirect_to_customer_portal(): RedirectResponse
    {
        $latest_active_subscription = auth()->user()->latestSubscription();

        if (is_null($latest_active_subscription)) {
            return redirect()->route('settings.subscription');
        }

        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        $stripe = new StripeClient(config('wave.stripe.secret_key'));

        // Uses the portal settings from the Stripe dashboard
        $billingPortal = $stripe->billingPortal->sessions->create([
            'customer' => $latest_active_subscription->vendor_customer_id,
            'return_url' => route('settings.subscription'),
        ]);

        return redirect()->to($billingPortal->url);

    }
}
