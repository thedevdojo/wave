<?php

namespace Wave\Http\Controllers\Billing\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;
use UnexpectedValueException;
use Wave\Plan;
use Wave\Subscription;

class StripeWebhook extends Controller
{
    public function handler(Request $request): Response
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature'),
                config('wave.stripe.webhook_secret')
            );
        } catch (UnexpectedValueException|SignatureVerificationException $e) {
            return response('Invalid payload or signature', 400);
        }

        if ($event->type == 'checkout.session.completed'
            || $event->type == 'checkout.session.async_payment_succeeded') {
            $this->fulfill_checkout($event->data->object->id);
        }

        // This event occurs when someone updates information in their customer portal.
        // This could be cancelling a subscription or it could be changing their plan.
        if ($event->type == 'customer.subscription.updated') {
            $stripeSubscription = $event->data->object;

            $subscription = $this->activeSubscription($stripeSubscription->id);

            // Stripe never reactivates these. past_due is left alone while Stripe retries the payment.
            if (isset($subscription) && in_array($stripeSubscription->status, ['canceled', 'incomplete_expired'])) {
                $subscription->cancel();
                $subscription->user->clearUserCache();
            } elseif (isset($subscription)) {
                // Interval should be 'year' or 'month'
                $subscriptionCycle = $stripeSubscription->plan->interval;
                $plan_price_column = ($subscriptionCycle == 'year') ? 'yearly_price_id' : 'monthly_price_id';
                $updatedPlan = Plan::where($plan_price_column, $stripeSubscription->plan->id)->first();

                if ($updatedPlan) {
                    $subscription->user->switchPlans($updatedPlan);
                    $subscription->cycle = $subscriptionCycle;
                    $subscription->plan_id = $updatedPlan->id;
                } else {
                    Log::warning("Stripe price {$stripeSubscription->plan->id} isn't mapped to a plan, keeping the current plan for {$stripeSubscription->id}.");
                }

                // this would be true if the user decides to cancel their subscription
                if (is_null($stripeSubscription->cancel_at)) {
                    $subscription->ends_at = null;
                } else {
                    $subscription->ends_at = Carbon::createFromTimestamp($stripeSubscription->cancel_at)->toDateTimeString();
                }

                $subscription->save();
                $subscription->user->clearUserCache();
            }
        }

        // Status docs here: https://docs.stripe.com/api/events/types#event_types-customer.subscription.deleted
        if ($event->type == 'customer.subscription.deleted') {
            $stripeSubscription = $event->data->object;

            $subscription = $this->activeSubscription($stripeSubscription->id);
            if (isset($subscription)) {
                $subscription->cancel();
                $subscription->user->clearUserCache();
            }
        }

        return response('Webhook handled', 200);
    }

    // Cancelled subscriptions are skipped so late or retried events can't change the user's roles.
    private function activeSubscription(string $vendorSubscriptionId): ?Subscription
    {
        return Subscription::where('vendor_slug', 'stripe')
            ->where('vendor_subscription_id', $vendorSubscriptionId)
            ->where('status', '!=', 'cancelled')
            ->first();
    }

    public function fulfill_checkout($session_id): void
    {
        Stripe::setApiKey(config('wave.stripe.secret_key'));

        $checkout_session = Session::retrieve($session_id);

        // Async payment methods complete unpaid, async_payment_succeeded follows once paid
        if ($checkout_session->payment_status == 'unpaid') {
            return;
        }

        if (Subscription::where('vendor_slug', 'stripe')->where('vendor_subscription_id', $checkout_session->subscription)->exists()) {
            return;
        }

        $user = User::findOrFail($checkout_session->metadata->billable_id);
        $plan = Plan::findOrFail($checkout_session->metadata->plan_id);

        // A concurrent duplicate fails on the unique index before any roles change
        DB::transaction(function () use ($checkout_session, $user, $plan) {
            Subscription::create([
                'billable_type' => $checkout_session->metadata->billable_type,
                'billable_id' => $user->id,
                'plan_id' => $plan->id,
                'vendor_slug' => 'stripe',
                'vendor_customer_id' => $checkout_session->customer,
                'vendor_subscription_id' => $checkout_session->subscription,
                'cycle' => $checkout_session->metadata->billing_cycle,
                'status' => 'active',
                'seats' => 1,
            ]);

            $user->syncRoles([]);
            $user->assignRole($plan->role->name);
        });

        $user->clearUserCache();
    }
}
