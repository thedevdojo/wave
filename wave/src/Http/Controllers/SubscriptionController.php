<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Wave\Plan;
use Wave\User;
use Wave\PaddleSubscription;
use TCG\Voyager\Models\Role;

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

    public function test(){
        $plans = Plan::all();
        $plan_ids = $plans->pluck('plan_id');
        dd($this->vendor_auth_code);
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

                        // create a new user
                        $registration = new \Wave\Http\Controllers\Auth\RegisterController;

                        $user_data = [
                            'name' => '',
                            'email' => $subscription->user_email,
                            'password' => Hash::make(uniqid())
                        ];

                        $user = $registration->create($user_data);

                        Auth::login($user);

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
                        'status' => 'active', // https://paddle.com/docs/subscription-status-reference/
                        'next_bill_data' => \Carbon\Carbon::now()->addMonths(1)->toDateTimeString(),
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


    // public function register(Request $request)
    // {

    //     $this->validator($request->all())->validate();

    //     DB::beginTransaction();

    //     event(new Registered($user = $this->create($request->all())));

    //     try{
    //         try {

    //             $plan = Plan::where('plan_id', '=', $request->plan)->first();
    //             if(!isset($plan->id)){
    //                 DB::rollBack();
    //                 return back()->withInput($request->all())->with(['message' => 'Invalid Plan Selected', 'message_type' => 'danger']);;
    //             }

    //             $userSubscription = $user->newSubscription('main', $request->plan);

    //             if(intval($plan->trial_days) > 0){
    //                 $userSubscription = $userSubscription->trialUntil(Carbon::now()->addDays($plan->trial_days));
    //                 $user->trial_ends_at = now()->addDays($plan->trial_days);
    //             } else {
    //                 $user->trial_ends_at = NULL;
    //             }


    //             $userSubscription->create($request->paymentMethod, ['email' => $user->email]);

    //         } catch(\Stripe\Error\Card $e) {

    //             DB::rollBack();
    //             $error_message = 'Something went wrong with your card. Please make sure you are entering it correctly';
    //             if(isset($e->getJsonBody()['error']['message'])){
    //                $error_message = 'Sorry, there was an error with your card: ' . $error_body['error']['message'];
    //             }
    //             return back()->with(array('message' => $error_message, 'message_type' => 'danger'));
    //         }
    //     } catch(\Exception $e){
    //         return back()->with(array('message' => $e->getMessage(), 'message_type' => 'danger'));
    //     }

    //     $user->role_id = $plan->role_id;
    //     $user->save();

    //     DB::commit();

    //     $this->guard()->login($user);

    //     return $this->registered($request, $user)
    //                 ?: redirect($this->redirectPath())->with(['message' => 'Thanks for becoming a subscriber!', 'message_type' => 'success']);

    // }


    /**
     * Subscribe user to a plan
     *
     * @return \Illuminate\Http\Response
     */
    // public function subscribe(Request $request)
    // {

    //     try{
    //         try {

    //             $plan = Plan::where('plan_id', '=', $request->plan)->first();

    //             if(!isset($plan->id)){
    //                 return back()->withInput($request->all())->with(['note' => 'Invalid Plan Selected', 'note_type' => 'error']);;
    //             }

    //             $userSubscription = auth()->user()->newSubscription('main', $request->plan)->create($request->paymentMethod, ['email' => auth()->user()->email]);

    //         } catch(\Stripe\Error\Card $e) {
    //             $error_message = 'Something went wrong with your card. Please make sure you are entering it correctly';
    //             if(isset($e->getJsonBody()['error']['message'])){
    //                $error_message = 'Sorry, there was an error with your card: ' . $error_body['error']['message'];
    //             }
    //             return back()->with(array('note' => $error_message, 'note_type' => 'error'));
    //         }
    //     } catch(\Exception $e){
    //         return back()->with(array('message' => $e->getMessage(), 'message_type' => 'danger'));
    //     }

    //     auth()->user()->role_id = $plan->role_id;
    //     auth()->user()->trial_ends_at = NULL;
    //     auth()->user()->save();

    //     return back()->with(['message' => 'Successfully upgraded your account. Thanks for becoming a subscriber!', 'message_type' => 'success']);

    // }

    // public function update_plans(Request $request){

    //     $plan = Plan::where('plan_id', '=', $request->plan)->first();

    //     if(!isset($plan->id)){
    //         return back()->withInput($request->all())->with(['note' => 'Invalid Plan Selected', 'note_type' => 'error']);;
    //     }

    //     auth()->user()->subscription('main')->swap($plan->plan_id);
    //     auth()->user()->role_id = $plan->role_id;
    //     auth()->user()->save();

    //     return back()->with(['message' => 'Successfully Switched Subscription Plan.', 'message_type' => 'success']);

    // }

    // public function update_credit_card(Request $request)
    // {
    //     try {

    //         auth()->user()->updateDefaultPaymentMethod($request->paymentMethod);

    //         return back()->with(array('message' => 'Your card has been successfully updated. Thanks.', 'message_type' => 'success'));

    //     } catch (Exception $e) {
    //         return back()->with(array('message' => 'Sorry, there was an error with your card: ' . $e->getMessage(), 'message_type' => 'error'));
    //     }
    // }

    // public function cancel(Request $request){

    //     auth()->user()->subscription('main')->cancel();
    //     return back()->with(array('message' => 'Your subscription has been cancelled.', 'message_type' => 'info'));

    // }

    // public function reactivate(Request $request){

    //     auth()->user()->subscription('main')->resume();
    //     return back()->with(array('message' => 'You have successfully reactivated your account.', 'message_type' => 'success'));

    // }

}
