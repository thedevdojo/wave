<?php

namespace Wave\Http\Controllers\Billing\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stripe\Checkout\Session;
use Wave\Plan;
use Wave\Subscription;

class StripeWebhook extends Controller
{
    public function handler(Request $request)
    {
        $payload = $request->getContent();

        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                config('wave.stripe.webhook_secret')
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        if ($event->type == 'checkout.session.completed'
            || $event->type == 'checkout.session.async_payment_succeeded') {
            $this->fulfill_checkout($event->data->object->id, $event);
        }

        // This event occurs when someone updates information in their customer portal.
        // This could be cancelling a subscription or it could be changing their plan.
        if ($event->type == 'customer.subscription.updated') {
            $stripeSubscription = $event->data->object;

            $subscription = Subscription::where('vendor_subscription_id', $stripeSubscription->id)->first();
            if (isset($subscription)) {
                // Interval should be 'year' or 'month'
                $subscriptionCycle = $stripeSubscription->plan->interval;
                $plan_price_column = ($subscriptionCycle == 'year') ? 'yearly_price_id' : 'monthly_price_id';
                $updatedPlan = Plan::where($plan_price_column, $stripeSubscription->plan->id)->first();

                // TODO: Test that this works
                $subscription->user->switchPlans($updatedPlan);

                $subscription->cycle = $subscriptionCycle;
                $subscription->plan_id = $updatedPlan->id;

                // this would be true if the user decides to cancel their subscription
                if (is_null($stripeSubscription->cancel_at)) {
                    $subscription->ends_at = null;
                } else {
                    $subscription->ends_at = \Carbon\Carbon::createFromTimestamp($stripeSubscription->cancel_at)->toDateTimeString();
                }

                $subscription->save();
            }
        }

        // Status docs here: https://docs.stripe.com/api/events/types#event_types-customer.subscription.deleted
        if ($event->type == 'customer.subscription.deleted') {
            $stripeSubscription = $event->data->object;

            $subscription = Subscription::where('vendor_subscription_id', $stripeSubscription->id)->first();
            if (isset($subscription)) {
                $subscription->cancel();
            }
        }

        http_response_code(200);
    }

    public function fulfill_checkout($session_id, $event): void
    {
        $stripe = \Stripe\Stripe::setApiKey(config('wave.stripe.secret_key'));

        // Make this function safe to run multiple times,
        // even concurrently, with the same session ID
        $cacheKey = 'stripe_checkout_session_'.$session_id;
        if (Cache::has($cacheKey)) {
            return; // Session ID already processed, exit early
        }

        Cache::put($cacheKey, true, now()->addHours(24)); // Store session ID in cache for 24 hours

        // Retrieve the Checkout Session from the API with line_items expanded
        $checkout_session = Session::retrieve($session_id);

        // Check the Checkout Session's payment_status property
        // to determine if fulfillment should be peformed
        if ($checkout_session->payment_status != 'unpaid') {

            $existingSubscription = Subscription::where('vendor_subscription_id', $checkout_session->subscription)->first();
            if ($existingSubscription) {
                // This is a failsafe to make sure this method doesn't get called multiple times, if existing subscription, return
                return;
            }

            $billable_id = $checkout_session->metadata->billable_id;
            $billable_type = $checkout_session->metadata->billable_type;
            $plan_id = $checkout_session->metadata->plan_id;
            $billing_cycle = $checkout_session->metadata->billing_cycle;

            $user = \App\Models\User::find($billable_id);

            $plan = Plan::find($plan_id);
            $user->syncRoles([]);
            $user->assignRole($plan->role->name);

            Subscription::create([
                'billable_type' => $billable_type,
                'billable_id' => $billable_id,
                'plan_id' => $plan_id,
                'vendor_slug' => 'stripe',
                'vendor_customer_id' => $checkout_session->customer,
                'vendor_subscription_id' => $checkout_session->subscription,
                'cycle' => $billing_cycle,
                'status' => 'active',
                'seats' => 1,
            ]);
        }
    }
}
