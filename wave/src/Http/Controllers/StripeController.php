<?php

namespace Wave\Http\Controllers;

use Wave\Plan;
use Wave\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewUserPassword;

class StripeController extends Controller
{

    public function postCreateCheckoutSession(Request $request)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $sessionData = [
            'success_url' => route('stripe.success-checkout') . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('stripe.error-checkout'),
            'line_items' => [
                [
                    'price' => $request->get('planId'),
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'tax_id_collection' => [
                'enabled' => env('CASHIER_STRIPE_CALCULATE_TAXES')
            ],
            'allow_promotion_codes' => env('CASHIER_STRIPE_ALLOW_PROMO_CODES'),
            'locale' => "auto",
        ];

        if (auth()->check() && auth()->user()->stripe_id) {
            $sessionData['customer'] = auth()->user()->stripe_id;
        }

        if (auth()->check() && !auth()->user()->stripe_id) {
            $sessionData['customer_email'] = auth()->user()->email;
        }

        $session = $stripe->checkout->sessions->create($sessionData);

        return response()->json($session, 200);
    }

    /**
     * Handle the success Stripe Checkout
     */
    public function postSuccessCheckout(Request $request)
    {
        if (!$request->get('session_id')) abort(401);
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $session = $stripe->checkout->sessions->retrieve($request->get('session_id'));
        $customer = $stripe->customers->retrieve($session->customer);
        if (!$session || !$customer) abort(401);

        $subscription = $stripe->subscriptions->retrieve($session->subscription);

        $user = $this->createUserFromCheckout($customer);
        $this->createStripeSubscription($user, $subscription);

        // Update User role
        $plan = Plan::where('plan_id', $subscription->plan->id)->first();
        // add associated role to user
        $user->role_id = $plan->role_id;
        $user->save();

        // Authenticate the user
        Auth::login($user);


        session()->flash('complete');
        return view('theme::welcome');
    }

    public function postErrorCheckout(Request $request)
    {
    }

    private function createUserFromCheckout(\Stripe\Customer $customer)
    {
        $user = User::where('email', $customer->email)->first();
        if ($user) {
            if (!$user->stripe_id) {
                $user->stripe_id = $customer->id;
                $user->save();

                $user->syncStripeCustomerDetails();;
            }
            session()->flash('existing_customer');
            return $user;
        };

        $role = \TCG\Voyager\Models\Role::where('name', '=', config('voyager.user.default_role'))->first();

        $verification_code = NULL;
        $verified = 1;

        if (setting('auth.verify_email', false)) {
            $verification_code = str_random(30);
            $verified = 0;
        }

        $username = $this->getUniqueUsernameFromEmail($customer->email);

        $username_original = $username;
        $counter = 1;

        while (User::where('username', '=', $username)->first()) {
            $username = $username_original . (string)$counter;
            $counter += 1;
        }

        $trial_days = setting('billing.trial_days', 0);
        $trial_ends_at = null;
        // if trial days is not zero we will set trial_ends_at to ending date
        if (intval($trial_days) > 0) {
            $trial_ends_at = now()->addDays(setting('billing.trial_days', 0));
        }

        $newPassword = Str::random(16);

        $user = User::create([
            'name' => $customer->name,
            'email' => $customer->email,
            'stripe_id' => $customer->id,
            'username' => $username,
            'password' => bcrypt($newPassword),
            'role_id' => $role->id,
            'verification_code' => $verification_code,
            'verified' => $verified,
            'trial_ends_at' => $trial_ends_at
        ]);

        // if($user) {
        //     $user->notify(new NewUserPassword($user, $newPassword));
        // }

        return $user;
    }

    private function createStripeSubscription($user,  \Stripe\Subscription $stripeSubscription)
    {
        if (!$user->hasDefaultPaymentMethod()) {
            $user->updateDefaultPaymentMethod($stripeSubscription->default_payment_method);
        }

        if ($user->subscriptions()->count()) return false;

        // Manually add subscription to our database. The Stripe subscription has already been added throw checkout
        /** @var \Stripe\SubscriptionItem $firstItem */
        $firstItem = $stripeSubscription->items->first();
        $isSinglePrice = $stripeSubscription->items->count() === 1;

        /** @var \Laravel\Cashier\Subscription $subscription */
        $subscription = $user->subscriptions()->create([
            'name' => 'default',
            'stripe_id' => $stripeSubscription->id,
            'stripe_status' => $stripeSubscription->status,
            'stripe_price' => $isSinglePrice ? $firstItem->price->id : null,
            'quantity' => $isSinglePrice ? $firstItem->quantity : null,
            'trial_ends_at' => $stripeSubscription->trial_end,
            'ends_at' => null,
        ]);

        /** @var \Stripe\SubscriptionItem $item */
        foreach ($stripeSubscription->items as $item) {
            $subscription->items()->create([
                'stripe_id' => $item->id,
                'stripe_product' => $item->price->product,
                'stripe_price' => $item->price->id,
                'quantity' => $item->quantity ?? null,
            ]);
        }

        return $subscription;
    }

    public function getBillingPortal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(url()->previous());
    }

    private function getUniqueUsernameFromEmail($email)
    {
        $username = strtolower(trim(str_slug(explode('@', $email)[0])));

        $new_username = $username;

        $user_exists = \Wave\User::where('username', '=', $username)->first();
        $counter = 1;
        while (isset($user_exists->id)) {
            $new_username = $username . $counter;
            $counter += 1;
            $user_exists = \Wave\User::where('username', '=', $new_username)->first();
        }

        $username = $new_username;

        if (strlen($username) < 4) {
            $username = $username . uniqid();
        }

        return strtolower($username);
    }
}
