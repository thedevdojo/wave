<?php

namespace Wave\Actions\Billing\Paddle;

use Illuminate\Support\Facades\Http;
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
    public function __invoke($transactionId)
    {
        $endpoint = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';

        $retryCount = 5;
        $retryDelay = 3;
        $transaction = null;
        $response = Http::withToken(config('wave.paddle.api_key'))->get($endpoint.'/transactions/'.$transactionId);

        // There are times where the transaction is available, but the subscription ID is not available yet. Account for that.
        for ($i = 0; $i < $retryCount; $i++) {
            $response = Http::withToken(config('wave.paddle.api_key'))->get($endpoint.'/transactions/'.$transactionId);
            if ($response->successful()) {
                $resBody = json_decode($response->body());
                if (isset($resBody->data->status) && ! is_null($resBody->data->subscription_id)) {
                    $transaction = $resBody->data;
                    break;
                }
            }

            sleep($retryDelay);
        }

        if ($transaction) {

            $subscription = json_decode(Http::withToken(config('wave.paddle.api_key'))->get($endpoint.'/subscriptions/'.$transaction->subscription_id))->data;

            $latestSubscription = Subscription::where('vendor_transaction_id', $transaction->id)->where('status', 'active')->latest()->first();
            $latestSubscription->vendor_subscription_id = $subscription->id;
            $latestSubscription->save();

            return $latestSubscription;

        }

        return null;
    }
}
