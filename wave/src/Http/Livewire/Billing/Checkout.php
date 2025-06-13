<?php

namespace Wave\Http\Livewire\Billing;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;
use Stripe\StripeClient;
use Wave\Actions\Billing\Paddle\AddSubscriptionIdFromTransaction;
use Wave\Plan;
use Wave\Subscription;

class Checkout extends Component
{
    public $billing_cycle_available = 'month'; // month, year, or both;

    public $billing_cycle_selected = 'month';

    public $billing_provider;

    public $paddle_url;

    public $change = false;

    public $userSubscription = null;

    public $userPlan = null;

    public function mount()
    {
        $this->billing_provider = config('wave.billing_provider', 'stripe');
        $this->paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
        $this->updateCycleBasedOnPlans();

        if ($this->change) {
            // if we are changing the user plan as opposecd to checking out the first time.
            $this->userSubscription = auth()->user()->subscription;
            $this->userPlan = auth()->user()->subscription->plan;
        }
    }

    public function redirectToStripeCheckout(Plan $plan)
    {
        $stripe = new StripeClient(config('wave.stripe.secret_key'));

        $price_id = $this->billing_cycle_selected == 'month' ? $plan->monthly_price_id : $plan->yearly_price_id ?? null;

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price' => $price_id,
                'quantity' => 1,
            ]],
            'metadata' => [
                'billable_type' => 'user',
                'billable_id' => auth()->user()->id,
                'plan_id' => $plan->id,
                'billing_cycle' => $this->billing_cycle_selected,
            ],
            'mode' => 'subscription',
            'success_url' => url('subscription/welcome'),
            'cancel_url' => url('settings/subscription'),
        ]);

        return redirect($checkout_session->url);
    }

    public function updateCycleBasedOnPlans()
    {
        $plans = Plan::where('active', 1)->get();
        $hasMonthly = false;
        $hasYearly = false;
        foreach ($plans as $plan) {
            if (! empty($plan->monthly_price_id)) {
                $hasMonthly = true;
            }
            if (! empty($plan->yearly_price_id)) {
                $hasYearly = true;
            }
        }
        if ($hasMonthly && $hasYearly) {
            $this->billing_cycle_available = 'both';
        } elseif ($hasMonthly) {
            $this->billing_cycle_available = 'month';
        } elseif ($hasYearly) {
            $this->billing_cycle_available = 'year';
            $this->billing_cycle_selected = 'year';
        }
    }

    #[On('savePaddleSubscription')]
    public function savePaddleSubscription($transactionId)
    {
        $subscription = app(AddSubscriptionIdFromTransaction::class)($transactionId);
        if (! is_null($subscription)) {
            return redirect('/subscription/welcome');
        }

        $this->js('closeLoader()');
        Notification::make()
            ->title('Unable to obtain subscription information from payment provider.')
            ->danger()
            ->send();
    }

    #[On('verifyPaddleTransaction')]
    public function verifyPaddleTransaction($transactionId)
    {

        $transaction = null;

        $response = Http::withToken(config('wave.paddle.api_key'))->get($this->paddle_url.'/transactions/'.$transactionId);

        if ($response->successful()) {
            $resBody = json_decode($response->body());
            if (isset($resBody->data->status) && ($resBody->data->status == 'paid' || $resBody->data->status == 'completed' || $resBody->data->status == 'ready')) {
                $transaction = $resBody->data;
            }
        }

        if ($transaction) {
            // Proceed with processing the transaction

            $user = auth()->user();

            if ($this->billing_cycle_selected == 'month') {
                $plan = Plan::where('monthly_price_id', $transaction->items[0]->price->id)->first();
            } else {
                $plan = Plan::where('yearly_price_id', $transaction->items[0]->price->id)->first();
            }

            if (! isset($plan->id)) {
                $this->js('Paddle.Checkout.close()');
                Notification::make()
                    ->title('Plan Price ID not found. Something went wrong during the checkout process')
                    ->success()
                    ->send();

                return;
            }

            auth()->user()->syncRoles([]);
            auth()->user()->assignRole($plan->role->name);

            Subscription::create([
                'billable_type' => 'user',
                'billable_id' => auth()->user()->id,
                'plan_id' => $plan->id,
                'vendor_slug' => 'paddle',
                'vendor_transaction_id' => $transactionId,
                'vendor_customer_id' => $transaction->customer_id,
                'vendor_subscription_id' => $transaction->subscription_id,
                'cycle' => $this->billing_cycle_selected,
                'status' => 'active',
                'seats' => 1,
            ]);

            $this->js('savePaddleSubscription("'.$transactionId.'")');

        } else {
            $this->js('Paddle.Checkout.close()');
            Notification::make()
                ->title('Error processing the transaction. Please try again.')
                ->danger()
                ->send();
        }

        // if we got here something went wrong and we need to let the user know.

    }

    public function switchPlan(Plan $plan)
    {
        $subscription = auth()->user()->subscription;

        $price_id = ($this->billing_cycle_selected == 'month') ? $plan->monthly_price_id : $plan->yearly_price_id ?? null;

        $response = Http::withToken(config('wave.paddle.api_key'))->patch(
            $this->paddle_url.'/subscriptions/'.$subscription->vendor_subscription_id,
            [
                'items' => [
                    [
                        'price_id' => $price_id,
                        'quantity' => 1,
                    ],
                ],
                'proration_billing_mode' => 'prorated_immediately',
            ]
        );

        if ($response->successful()) {
            $subscription->plan_id = $plan->id;
            $subscription->cycle = $this->billing_cycle_selected;
            $subscription->save();
            $subscription->user->switchPlans($plan);

            return redirect('/settings/subscription')->with(['update' => true]);
        }
    }

    public function render()
    {
        return view('wave::livewire.billing.checkout', [
            'plans' => Plan::where('active', 1)->get(),
        ]);
    }
}
