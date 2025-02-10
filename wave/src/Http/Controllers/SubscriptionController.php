<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use TCG\Voyager\Models\Role;
use Wave\Plan;
use Wave\Subscription;
use Wave\User;

class SubscriptionController extends Controller
{
    private $paddle_url;

    private $vendor_id;

    private $api_key;

    public function __construct()
    {
        $this->api_key = config('wave.paddle.api_key');
        $this->vendor_id = config('wave.paddle.vendor');

        $this->paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
    }

    public function cancel(Request $request)
    {
        $this->cancelSubscription($request->id);

        return response()->json(['status' => 1]);
    }

    private function cancelSubscription()
    {
        // Ensure user is authenticated
        if (! auth()->check()) {
            return redirect('/login')->with(['message' => 'Please log in to continue.', 'message_type' => 'danger']);
        }

        // Auth user get latest subscription id
        $subscription_id = auth()->user()->latestSubscription->subscription_id;

        // Ensure the provided subscription ID matches the user's subscription ID
        $localSubscription = Subscription::where('subscription_id', $subscription_id)->first();

        if (! $localSubscription || auth()->user()->latestSubscription->subscription_id != $subscription_id) {
            return back()->with(['message' => 'Invalid subscription ID.', 'message_type' => 'danger']);
        }

        $response = Http::withToken($this->api_key)
            ->post($this->paddle_url.'/subscriptions/'.$subscription_id.'/cancel', [
                'effective_from' => 'immediately',
            ]);

        \Illuminate\Support\Facades\Log::info($response->body());

        // Check if the request was successful
        if ($response->successful()) {
            $body = $response->json();

            if (isset($body['data']) && isset($body['data']['status']) && $body['data']['status'] == 'canceled') {

                // Update subscription in local database
                $localSubscription->cancelled_at = Carbon::parse($body['data']['canceled_at']);
                $localSubscription->status = 'cancelled';
                $localSubscription->save();

                // Update user's role to "cancelled"
                $user = User::find($localSubscription->user_id);
                $cancelledRole = Role::where('name', '=', 'cancelled')->first();
                $user->role_id = $cancelledRole->id;
                $user->save();

                return back()->with(['message' => 'Your subscription has been successfully canceled.', 'message_type' => 'success']);
            } else {
                // Handle any errors that were returned in the response body
                $error = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown error while canceling the subscription.';

                return back()->with(['message' => $error, 'message_type' => 'danger']);
            }
        } else {
            // Handle failed HTTP requests
            return back()->with(['message' => 'Failed to cancel the subscription. Please try again later.', 'message_type' => 'danger']);
        }
    }

    public function checkout(Request $request)
    {
        $retryCount = 5;
        $initialDelay = 2;
        $transaction = null;
        $status = 0;
        $message = '';
        $guest = (auth()->guest()) ? 1 : 0;

        for ($i = 0; $i < $retryCount; $i++) {
            $response = Http::withToken($this->api_key)->get($this->paddle_url.'/transactions/'.$request->checkout_id);

            \Illuminate\Support\Facades\Log::info($response->body());
            if ($response->successful()) {
                $resBody = json_decode($response->body());
                if (isset($resBody->data->status) && ! is_null($resBody->data->subscription_id)) {
                    $transaction = $resBody->data;
                    break;
                }
            }

            sleep($initialDelay * (2 ** $i));
        }

        if ($transaction) {
            // Proceed with processing the transaction
            $plans = Plan::all();
            if ($transaction->origin === 'web' && $plans->contains('plan_id', $transaction->items[0]->price->id)) {
                $subscriptionUser = Http::withToken($this->api_key)->get($this->paddle_url.'/subscriptions/'.$transaction->subscription_id);
                $subscriptionData = json_decode($subscriptionUser->body());
                $subscription = $subscriptionData->data;

                $customerResponse = Http::withToken($this->api_key)->get($this->paddle_url.'/customers/'.$subscription->customer_id);
                $customerData = json_decode($customerResponse->body());
                $customerEmail = $customerData->data->email;
                $customerName = $customerData->data->name;
                if (empty($customerName)) {
                    $nameParts = explode('@', $customerEmail);
                    $customerName = $nameParts[0];
                }

                if ($guest) {
                    if (User::where('email', $customerEmail)->exists()) {
                        $user = User::where('email', $customerEmail)->first();
                    } else {
                        $registration = new \Wave\Http\Controllers\Auth\RegisterController;
                        $user_data = [
                            'name' => $customerName,
                            'email' => $customerEmail,
                            'password' => Hash::make(uniqid()),
                        ];
                        $user = $registration->create($user_data);
                        Auth::login($user);
                    }
                } else {
                    $user = auth()->user();
                }

                $plan = Plan::where('plan_id', $transaction->items[0]->price->id)->first();

                // Update user role based on plan
                $user->role_id = $plan->role_id;
                $user->save();

                // Create or update subscription details
                $subscriptionRecord = Subscription::create([
                    'subscription_id' => $transaction->subscription_id,
                    'plan_id' => $transaction->items[0]->price->product_id,
                    'user_id' => $user->id,
                    'status' => $subscription->status,
                    'last_payment_at' => $subscription->first_billed_at,
                    'next_payment_at' => $subscription->next_billed_at,
                    'cancel_url' => $subscription->management_urls->cancel,
                    'update_url' => $subscription->management_urls->update_payment_method,
                ]);

                $status = 1;
            } else {
                $message = 'Error locating that subscription product id. Please contact us if you think this is incorrect.';
            }
        } else {
            $message = 'Error processing the transaction. Please try again.';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'guest' => $guest,
        ]);
    }

    public function transactions(User $user)
    {

        // Check if user has a subscription
        if (! $user->latestSubscription) {
            return [];
        }

        $invoices = [];
        $response = Http::withToken($this->api_key)->get($this->paddle_url.'/transactions', [
            'subscription_id' => $user->latestSubscription->subscription_id,
        ]);

        $transactions = json_decode($response->body());

        return $transactions->data;

    }

    public function invoice(Request $request, $transactionId)
    {

        $response = Http::withToken($this->api_key)->get($this->paddle_url.'/transactions/'.$transactionId.'/invoice');
        $invoice = json_decode($response->body());

        // redirect user to the invoice download URL
        return redirect($invoice->data->url);
    }

    public function switchPlans(Request $request)
    {
        $plan = Plan::where('plan_id', $request->plan_id)->first();

        if (isset($plan->id)) {
            // Update the user plan with Paddle
            $response = Http::withToken($this->api_key)->patch(
                $this->paddle_url.'/subscriptions/'.(string) $request->user()->latestSubscription->subscription_id,
                [
                    'items' => [
                        [
                            'price_id' => $plan->plan_id,
                            'quantity' => 1,
                        ],
                    ],
                    'proration_billing_mode' => 'prorated_immediately',
                ]
            );

            if ($response->successful()) {
                $body = $response->json();

                if (isset($body['data']) && $body['data']['status'] == 'active') {
                    // Update the user role associated with the updated plan
                    $request->user()->forceFill([
                        'role_id' => $plan->role_id,
                    ])->save();

                    // Update the subscription with the updated plan in the local database
                    $request->user()->subscription->update([
                        'plan_id' => $request->plan_id,
                    ]);

                    return back()->with(['message' => 'Successfully switched to the '.$plan->name.' plan.', 'message_type' => 'success']);
                }
            }
        }

        return back()->with(['message' => 'Sorry, there was an issue updating your plan.', 'message_type' => 'danger']);
    }
}
