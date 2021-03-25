<?php

namespace Wave\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Wave\Plan;

class RegisterController extends \App\Http\Controllers\Auth\RegisterController
{

    /**
     * Register a new user and subscribe
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        DB::beginTransaction();

        event(new Registered($user = $this->create($request->all())));

        try{
            try {

                $plan = Plan::where('plan_id', '=', $request->plan)->first();
                if(!isset($plan->id)){
                    DB::rollBack();
                    return back()->withInput($request->all())->with(['message' => 'Invalid Plan Selected', 'message_type' => 'danger']);;
                }

                $userSubscription = $user->newSubscription('main', $request->plan);

                if(intval($plan->trial_days) > 0){
                    $userSubscription = $userSubscription->trialUntil(Carbon::now()->addDays($plan->trial_days));
                    $user->trial_ends_at = now()->addDays($plan->trial_days);
                } else {
                    $user->trial_ends_at = NULL;
                }


                $userSubscription->create($request->paymentMethod, ['email' => $user->email]);

            } catch(\Stripe\Error\Card $e) {

                DB::rollBack();
                $error_message = 'Something went wrong with your card. Please make sure you are entering it correctly';
                if(isset($e->getJsonBody()['error']['message'])){
                   $error_message = 'Sorry, there was an error with your card: ' . $error_body['error']['message'];
                }
                return back()->with(array('message' => $error_message, 'message_type' => 'danger'));
            }
        } catch(\Exception $e){
            return back()->with(array('message' => $e->getMessage(), 'message_type' => 'danger'));
        }

        $user->role_id = $plan->role_id;
        $user->save();

        DB::commit();

        $this->guard()->login($user);

        return $this->registered($request, $user)
                    ?: redirect($this->redirectPath())->with(['message' => 'Thanks for becoming a subscriber!', 'message_type' => 'success']);

    }
}
