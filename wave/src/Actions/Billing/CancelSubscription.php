<?php

namespace Wave\Actions\Billing;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\InvalidRequestException;
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
            Log::warning('Cancelled a subscription with no vendor id locally only, check it with the vendor.', [
                'subscription_id' => $subscription->id,
                'vendor' => $subscription->vendor_slug,
            ]);
            $subscription->cancel();

            return true;
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
        $stripe = new StripeClient(config('wave.stripe.secret_key'));
        $id = $subscription->vendor_subscription_id;

        try {
            $stripe->subscriptions->cancel($id);
        } catch (InvalidRequestException $e) {
            // Already cancelled or gone on Stripe's side, nothing left to bill.
            if ($e->getHttpStatus() === 404) {
                return $this->vendorAlreadyGone($subscription);
            }

            if (! in_array($stripe->subscriptions->retrieve($id)->status, ['canceled', 'incomplete_expired'])) {
                throw $e;
            }
        }

        return true;
    }

    private function cancelWithPaddle(Subscription $subscription): bool
    {
        $url = '/subscriptions/'.$subscription->vendor_subscription_id;

        $response = $this->paddle()->post($url.'/cancel', ['effective_from' => 'immediately']);

        if ($response->successful()) {
            return true;
        }

        if ($response->status() === 404) {
            return $this->vendorAlreadyGone($subscription);
        }

        // The cancel call fails on a subscription that is already cancelled.
        return $this->paddle()->get($url)->json('data.status') === 'canceled';
    }

    private function paddle(): PendingRequest
    {
        $baseUrl = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';

        return Http::withToken(config('wave.paddle.api_key'))->baseUrl($baseUrl);
    }

    private function vendorAlreadyGone(Subscription $subscription): bool
    {
        Log::warning('Subscription was not found with the vendor, cancelling it locally.', [
            'subscription_id' => $subscription->id,
            'vendor' => $subscription->vendor_slug,
            'vendor_subscription_id' => $subscription->vendor_subscription_id,
        ]);

        return true;
    }
}
