<?php

namespace Wave\Http\Livewire\Billing;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
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

    #[Locked]
    public $billing_provider;

    #[Locked]
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

        return redirect()->to($checkout_session->url);
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
            return redirect()->to('/subscription/welcome');
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
        $user = auth()->user();
        $transaction = $this->paidPaddleTransaction((string) $transactionId);

        if (is_null($transaction)) {
            $this->js('Paddle.Checkout.close()');
            Notification::make()
                ->title('Error processing the transaction. Please try again.')
                ->danger()
                ->send();

            return;
        }

        $priceColumn = ($this->billing_cycle_selected == 'month') ? 'monthly_price_id' : 'yearly_price_id';
        $plan = Plan::where($priceColumn, data_get($transaction, 'items.0.price.id'))->first();

        if (! isset($plan->id)) {
            $this->js('Paddle.Checkout.close()');
            Notification::make()
                ->title('Plan Price ID not found. Something went wrong during the checkout process')
                ->danger()
                ->send();

            return;
        }

        DB::transaction(function () use ($user, $plan, $transaction, $transactionId) {
            Subscription::create([
                'billable_type' => 'user',
                'billable_id' => $user->id,
                'plan_id' => $plan->id,
                'vendor_slug' => 'paddle',
                'vendor_transaction_id' => $transactionId,
                'vendor_customer_id' => $transaction['customer_id'] ?? null,
                'vendor_subscription_id' => $transaction['subscription_id'] ?? null,
                'cycle' => $this->billing_cycle_selected,
                'status' => 'active',
                'seats' => 1,
            ]);

            $user->syncRoles([]);
            $user->assignRole($plan->role->name);
        });

        $user->clearUserCache();

        $this->js('savePaddleSubscription("'.e($transactionId).'")');
    }

    /**
     * The transaction if it is paid, was started by this user and hasn't been used yet.
     */
    private function paidPaddleTransaction(string $transactionId): ?array
    {
        if (! preg_match('/^txn_[a-z0-9]+$/', $transactionId)) {
            return null;
        }

        if (Subscription::where('vendor_slug', 'paddle')->where('vendor_transaction_id', $transactionId)->exists()) {
            return null;
        }

        $response = Http::withToken(config('wave.paddle.api_key'))->get($this->paddle_url.'/transactions/'.$transactionId);
        $transaction = $response->successful() ? $response->json('data') : null;

        if (! in_array($transaction['status'] ?? null, ['paid', 'completed'])) {
            return null;
        }

        // Set by openCheckout(), so another user's transaction can't be claimed
        if ((string) data_get($transaction, 'custom_data.user_id') !== (string) auth()->id()) {
            return null;
        }

        if (! empty($transaction['subscription_id'])
            && Subscription::where('vendor_slug', 'paddle')->where('vendor_subscription_id', $transaction['subscription_id'])->exists()) {
            return null;
        }

        return $transaction;
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
            $subscription->user->clearUserCache();

            return redirect()->to('/settings/subscription')->with(['update' => true]);
        }

        Notification::make()
            ->title('Your plan could not be changed. Please try again or contact support.')
            ->danger()
            ->send();
    }

    public function render()
    {
        return view('wave::livewire.billing.checkout', [
            'plans' => Plan::getActivePlans(),
        ]);
    }
}
