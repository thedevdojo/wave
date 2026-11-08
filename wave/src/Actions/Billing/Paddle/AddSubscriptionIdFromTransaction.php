<?php

namespace Wave\Actions\Billing\Paddle;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Wave\Subscription;

class AddSubscriptionIdFromTransaction
{
    /**
     * Add a subscription ID from a Transaction ID
     * Paddle API Docs
     *
     *
     * @return mixed
     */
    public function __invoke($transactionId, int $attempts = 5)
    {
        $endpoint = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';

        $retryDelay = 3;
        $subscriptionId = null;

        // There are times where the transaction is available, but the subscription ID is not available yet. Account for that.
        for ($i = 0; $i < $attempts; $i++) {
            if ($i > 0) {
                Sleep::for($retryDelay)->seconds();
            }

            $response = Http::withToken(config('wave.paddle.api_key'))->get($endpoint.'/transactions/'.$transactionId);
            if ($response->successful() && $response->json('data.subscription_id')) {
                $subscriptionId = $response->json('data.subscription_id');
                break;
            }
        }

        $latestSubscription = Subscription::where('vendor_transaction_id', $transactionId)->where('status', 'active')->latest()->first();

        if (is_null($subscriptionId) || is_null($latestSubscription)) {
            return;
        }

        $latestSubscription->vendor_subscription_id = $subscriptionId;
        $latestSubscription->save();

        return $latestSubscription;
    }
}
