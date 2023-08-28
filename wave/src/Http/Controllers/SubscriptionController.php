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

    private $paddle_url;

    private $vendor_id;
    private $vendor_auth_code;

    public function __construct(){
        $this->vendor_auth_code = config('wave.paddle.auth_code');
        $this->vendor_id = config('wave.paddle.vendor');

        $this->paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
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
        $response = Http::withToken($this->vendor_auth_code)->get($this->paddle_url . '/transactions/' . $request->checkout_id);
        $status = 0;
        $message = '';
        $guest = (auth()->guest()) ? 1 : 0;
    
        if($response->successful()){
            $resBody = json_decode($response->body());
    
            if(isset($resBody->data->status)){
                $transaction = $resBody->data;
                $plans = Plan::all();
    
                if($transaction->origin === "web" && $plans->contains('plan_id', $transaction->items[0]->price->id)){
                    $subscriptionUser = Http::withToken($this->vendor_auth_code)->get($this->paddle_url . '/subscriptions/' . $transaction->subscription_id);
                    $subscriptionData = json_decode($subscriptionUser->body());
                    $subscription = $subscriptionData->data; // Adjusted this from 'response[0]' to 'data'
                
                    // Fetch customer's details using the customer_id
                    $customerResponse = Http::withToken($this->vendor_auth_code)->get($this->paddle_url . '/customers/' . $subscription->customer_id);
                    $customerData = json_decode($customerResponse->body());
                    $customerEmail = $customerData->data->email;
                    $customerName = $customerData->data->name;
                    // Check if the name is null or empty
                    if (empty($customerName)) {
                        // Extract the part of the email address before the '@' symbol
                        $nameParts = explode('@', $customerEmail);
                        $customerName = $nameParts[0];
                    }

                    if(auth()->guest()){
                        if(User::where('email', $customerEmail)->exists()){
                            $user = User::where('email', $customerEmail)->first();
                        } else {
                            // create a new user
                            $registration = new \Wave\Http\Controllers\Auth\RegisterController;
                            $user_data = [
                                'name' => $customerName,
                                'email' => $customerEmail,
                                'password' => Hash::make(uniqid())
                            ];
                            $user = $registration->create($user_data);
                            Auth::login($user);
                        }
                    } else {
                        $user = auth()->user();
                    }
                
                    $plan = Plan::where('plan_id', $transaction->items[0]->price->id)->first();       
    
                    // add associated role to user
                    $user->role_id = $plan->role_id;
                    $user->save();
    
                    $subscription = PaddleSubscription::create([
                        'subscription_id' => $transaction->subscription_id,
                        'plan_id' => $transaction->items[0]->price->product_id,
                        'user_id' => $user->id,
                        'status' => $subscription->status,
                        'last_payment_at' => $subscription->first_billed_at,
                        'next_payment_at' => $subscription->next_billed_at,
                        'cancel_url' => $subscription->management_urls->cancel,
                        'update_url' => $subscription->management_urls->update_payment_method
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
            $response = Http::post($this->paddle_url . '/2.0/subscription/payments', [
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
            $response = Http::post($this->paddle_url . '/2.0/subscription/users/update', [
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
