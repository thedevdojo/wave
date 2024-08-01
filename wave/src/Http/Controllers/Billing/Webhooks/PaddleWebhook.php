<?php

namespace Wave\Http\Controllers\Billing\Webhooks;

use Wave\Plan;
use Wave\Subscription;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaddleWebhook extends Controller
{
    public $paddle_url;
    public function handler(Request $request)
    {
        $event = $request->get('event_type', null);

        dump('hit the handler');
        dump($event);

        dump('request');
        dump($request);

        Log::info('hit the handler');

        switch ($event) {
            case 'subscription.canceled':
            case 'subscription_payment_failed':
                $this->subscriptionCancelled($request);
                break;
            case 'subscription.created':
                $this->saveTransactionSubscriptionId($request);
                $this->getTheCancelAndUpdateURLfromSubscription($request);
                break;
            default:
                $method = null;
                break;
        }
    
        return response()->json(['message' => 'Webhook handled successfully'], 200);

    }

    protected function subscriptionCancelled(Request $request)
    {
        $subscriptionId = $request->input('data.id'); // Adjusted to match the payload structure
    
        // Ensure the subscription ID is provided
        if (is_null($subscriptionId)) {
            Log::warning('Subscription ID missing in subscriptionCancelled webhook.');
            return;
        }

    }

    protected function saveTransactionSubscriptionId(Request $request){
        $subscription = json_decode($request->getContent())->data;

        $latestSubscription = Subscription::where('vendor_transaction_id', $subscription->transaction_id)->where('status', 'active')->latest()->first();
        $latestSubscription->vendor_subscription_id = $subscription->id;
        $latestSubscription->save();
    } 
    
    protected function getTheCancelAndUpdateURLfromSubscription(Request $request){
        $this->paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
        $subscription = json_decode($request->getContent())->data;
        $response = Http::withToken( config('wave.paddle.api_key') )->get($this->paddle_url . '/subscriptions/' . $subscription->id);

        if (!$response->successful()) {
            Log::warning('Unable to get the Update and Cancel URLs for subscription id ' . $subscription->id); 
            return;   
        }

        $subscriptionInfo = json_decode($response->body());

        dump($subscriptionInfo);

        $subscription = Subscription::where('vendor_subscription_id', $subscription->id)->first();
        $subscription->cancel_url = $subscriptionInfo->data->management_urls->cancel;
        $subscription->update_url = $subscriptionInfo->data->management_urls->update_payment_method;

        $subscription->save();
    }
}
