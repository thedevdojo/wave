<?php

namespace Wave\Http\Controllers\Billing;

use App\Http\Controllers\Controller;

class Stripe extends Controller
{
    public function redirect_to_customer_portal()
    {

        $latest_active_subscription = auth()->user()->latestSubscription();
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        $stripe = new \Stripe\StripeClient(config('wave.stripe.secret_key'));

        $stripe->billingPortal->configurations->create([
            'business_profile' => [
                'headline' => config('app.name'),
            ],
            'features' => ['invoice_history' => ['enabled' => true]],
        ]);

        $billingPortal = $stripe->billingPortal->sessions->create([
            'customer' => $latest_active_subscription->vendor_customer_id,
            'return_url' => route('settings.subscription'),
        ]);

        return redirect($billingPortal->url);

    }
}
