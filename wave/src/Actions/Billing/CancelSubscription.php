<?php

namespace Wave\Actions\Billing;

use Illuminate\Support\Facades\Http;
use Stripe\StripeClient;
use Wave\Subscription;

class CancelSubscription
{
    /**
     * Cancel the subscription with its vendor right away, then locally.
     * Returns false when the vendor did not accept the cancellation.
     */
    public function __invoke(Subscription $subscription): bool
    {
        if (is_null($subscription->vendor_subscription_id)) {
            return false;
        }

        $cancelled = match ($subscription->vendor_slug) {
            'stripe' => $this->cancelWithStripe($subscription),
            'paddle' => $this->cancelWithPaddle($subscription),
            default => false,
        };

        if ($cancelled) {
            $subscription->cancel();
        }

        return $cancelled;
    }

    private function cancelWithStripe(Subscription $subscription): bool
    {
        // Throws on an API error.
        (new StripeClient(config('wave.stripe.secret_key')))->subscriptions->cancel($subscription->vendor_subscription_id);

        return true;
    }

    private function cancelWithPaddle(Subscription $subscription): bool
    {
        $paddleUrl = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';

        return Http::withToken(config('wave.paddle.api_key'))
            ->post($paddleUrl.'/subscriptions/'.$subscription->vendor_subscription_id.'/cancel', [
                'effective_from' => 'immediately',
            ])
            ->successful();
    }
}
