<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use TCG\Voyager\Models\Role;
use Wave\PaddleSubscription;
use Carbon\Carbon;
use Wave\Plan;
use Wave\User;

class SubscriptionController extends Controller
{

    private $paddle_checkout_url;
    private $paddle_vendors_url;
    private $endpoint = 'https://vendors.paddle.com/api';

    private $vendor_id;
    private $vendor_auth_code;

    public function __construct(){
        $this->vendor_auth_code = config('wave.paddle.auth_code');
        $this->vendor_id = config('wave.paddle.vendor');

        $this->paddle_checkout_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-checkout.paddle.com/api' : 'https://checkout.paddle.com/api';
        $this->paddle_vendors_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-vendors.paddle.com/api' : 'https://vendors.paddle.com/api';
    }


    public function webhook(Request $request){

        // Which alert/event is this request for?
        $alert_name = $request->alert_name;
        $subscription_id = $request->subscription_id;
        $status = $request->status;


        // Respond appropriately to this request.
        switch($alert_name) {

            case 'subscription_created':
                break;
            case 'subscription_updated':
                break;
            case 'subscription_cancelled':
                $this->cancelSubscription($subscription_id);
                return response()->json(['status' => 1]);
                break;
            case 'subscription_payment_succeeded':
                break;
            case 'subscription_payment_failed':
                $this->cancelSubscription($subscription_id);
                return response()->json(['status' => 1]);
                break;
        }

    }

    public function cancel(Request $request){
        $this->cancelSubscription($request->id);
        return response()->json(['status' => 1]);
    }

    private function cancelSubscription($subscription_id){
        $subscription = PaddleSubscription::where('subscription_id', $subscription_id)->first();
        $subscription->cancelled_at = Carbon::now();
        $subscription->status = 'cancelled';
        $subscription->save();
        $user = User::find( $subscription->user_id );
        $cancelledRole = Role::where('name', '=', 'cancelled')->first();
        $user->role_id = $cancelledRole->id;
        $user->save();
    }

    public function checkout(Request $request){

        //PaddleSubscriptions
        $response = Http::get($this->paddle_checkout_url . '/1.0/order?checkout_id=' . $request->checkout_id);
        $status = 0;
        $message = '';
        $guest = (auth()->guest()) ? 1 : 0;

        if( $response->successful() ){
            $resBody = json_decode($response->body());

            if(isset($resBody->order)){
                $order = $resBody->order;

                $plans = Plan::all();

                if($order->is_subscription && $plans->contains('plan_id', $order->product_id) ){

                    $subscriptionUser = Http::post($this->paddle_vendors_url . '/2.0/subscription/users', [
                        'vendor_id' => $this->vendor_id,
                        'vendor_auth_code' => $this->vendor_auth_code,
                        'subscription_id' => $order->subscription_id
                    ]);

                    $subscriptionData = json_decode($subscriptionUser->body());
                    $subscription = $subscriptionData->response[0];

                    if(auth()->guest()){

                        if(User::where('email', $subscription->user_email)->exists()){
                            $user = User::where('email', $subscription->user_email)->first();
                        } else {
                            // create a new user
                            $registration = new \Wave\Http\Controllers\Auth\RegisterController;

                            $user_data = [
                                'name' => '',
                                'email' => $subscription->user_email,
                                'password' => Hash::make(uniqid())
                            ];

                            $user = $registration->create($user_data);

                            Auth::login($user);
                        }

                    } else {
                        $user = auth()->user();
                    }

                    $plan = Plan::where('plan_id', $subscription->plan_id)->first();

                    // add associated role to user
                    $user->role_id = $plan->role_id;
                    $user->save();

                    $subscription = PaddleSubscription::create([
                        'subscription_id' => $order->subscription_id,
                        'plan_id' => $order->product_id,
                        'user_id' => $user->id,
                        'status' => 'active', // https://developer.paddle.com/reference/ZG9jOjI1MzU0MDI2-subscription-status-reference
                        'last_payment_at' => $subscription->last_payment->date,
                        'next_payment_at' => $subscription->next_payment->date,
                        'cancel_url' => $subscription->cancel_url,
                        'update_url' => $subscription->update_url
                    ]);

                    $status = 1;
                } else {

                    $message = 'Error locating that subscription product id. Please contact us if you think this is incorrect.';

                }
            } else {

                $message = 'Error locating that order. Please contact us if you think this is incorrect.';
            }

        } else {
            $message = $response->serverError();
        }

        return response()->json([
                    'status' => $status,
                    'message' => $message,
                    'guest' => $guest
                ]);
    }

    public function invoices(User $user){

        $invoices = [];

        if(isset($user->subscription->subscription_id)){
            $response = Http::post($this->paddle_vendors_url . '/2.0/subscription/payments', [
                'vendor_id' => $this->vendor_id,
                'vendor_auth_code' => $this->vendor_auth_code,
                'subscription_id' => $user->subscription->subscription_id,
                'is_paid' => 1
            ]);

            $invoices = json_decode($response->body());
        }

        return $invoices;

    }

    public function switchPlans(Request $request){
        $plan = Plan::where('plan_id', $request->plan_id)->first();

        if(isset($plan->id)){
            // Update the user plan with Paddle
            $response = Http::post($this->paddle_vendors_url . '/2.0/subscription/users/update', [
                'vendor_id' => $this->vendor_id,
                'vendor_auth_code' => $this->vendor_auth_code,
                'subscription_id' => $request->user()->subscription->subscription_id,
                'plan_id' => $request->plan_id
            ]);

            if($response->successful()){
                $body = $response->json();

                if($body['success']){
                    // Next, update the user role associated with the updated plan
                    $request->user()->forceFill([
                        'role_id' => $plan->role_id
                    ])->save();

                    // And, update the subscription with the updated plan.
                    $request->user()->subscription()->update([
                        'plan_id' => $request->plan_id
                    ]);

                    return back()->with(['message' => 'Successfully switched to the ' . $plan->name . ' plan.', 'message_type' => 'success']);
                }
            }

        }

        return back()->with(['message' => 'Sorry, there was an issue updating your plan.', 'message_type' => 'danger']);


    }

}
