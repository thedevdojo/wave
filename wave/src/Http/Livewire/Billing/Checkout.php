<?php

namespace Wave\Http\Livewire\Billing;

use Livewire\Component;
use Wave\Plan;
use Stripe\StripeClient;
use Livewire\Attributes\On;
use Wave\Subscription;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class Checkout extends Component
{
    public $billing_cycle_available = 'month'; // month, year, or both;
    public $billing_cycle_selected = 'month';

    public $billing_provider;

    public $paddle_url;

    public function mount()
    {
        $this->billing_provider = config('wave.billing_provider', 'stripe');
        $this->paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
        $this->updateCycleBasedOnPlans();
    }

    public function redirectToPaymentProvider(Plan $plan)
    {
        $redirect_url = ($this->billing_provider == 'paddle') ? $this->getPaymentRedirectFromPaddle($plan) : $this->getPaymentRedirectFromStripe($plan);

        return redirect($redirect_url);
    }

    public function updateCycleBasedOnPlans()
    {
        $plans = Plan::where('active', 1)->get();
        $hasMonthly = false;
        $hasYearly = false;
        foreach ($plans as $plan) {
            if (!empty($plan->monthly_price_id)) {
                $hasMonthly = true;
            }
            if (!empty($plan->yearly_price_id)) {
                $hasYearly = true;
            }
        }
        if ($hasMonthly && $hasYearly) {
            $this->billing_cycle_available = 'both';
        } elseif ($hasMonthly) {
            $this->billing_cycle_available = 'month';
        } elseif ($hasYearly) {
            $this->billing_cycle_available = 'year';
        }
    }

    private function getPaymentRedirectFromPaddle(Plan $plan){

    }

    #[On('confirmPaddleCheckout')] 
    public function confirmPaddleCheckout($transactionId){

        $transaction = null;

        $response = Http::withToken( config('wave.paddle.api_key') )->get($this->paddle_url . '/transactions/' . $transactionId);
        dump($response->body());
        if ($response->successful()) {
            $resBody = json_decode($response->body());
            if (isset($resBody->data->status) && ($resBody->data->status == 'paid' || $resBody->data->status == 'completed' || $resBody->data->status == 'ready')) {
                $transaction = $resBody->data;
            }
        }

        if ($transaction) {
            // Proceed with processing the transaction

            $user = auth()->user();

            if($this->billing_cycle_selected == 'month'){
                $plan = Plan::where('monthly_price_id', $transaction->items[0]->price->id)->first();
            } else {
                $plan = Plan::where('yearly_price_id', $transaction->items[0]->price->id)->first(); 
            }

            if(!isset($plan->id)){
                $this->js('Paddle.Checkout.close()');
                Notification::make()
                    ->title('Plan Price ID not found. Something went wrong during the checkout process')
                    ->success()
                    ->send();
                return;
            }

            // Update user role based on plan
            //$user->role_id = $plan->role_id;
            // $user->save();

            auth()->user()->syncRoles([]);
            auth()->user()->assignRole($plan->role->name);

            dump($transaction);

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
                'seats' => 1
            ]);

            return redirect('/subscription/welcome');
           
        } else {
            $this->js('Paddle.Checkout.close()');
            Notification::make()
                ->title('Error processing the transaction. Please try again.')
                ->danger()
                ->send();
        }

            // if we got here something went wrong and we need to let the user know.
         
    }

    private function getPaymentRedirectFromStripe(Plan $plan){
        $stripe = new StripeClient(config('wave.stripe.secret_key'));

        $price_id = $this->billing_cycle_selected == 'month' ? $plan->monthly_price_id : $plan->yearly_price_id ?? null;

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price' => $price_id,
                'quantity' => 1
            ]],
            'metadata' => [
                'billable_type' => 'user',
                'billable_id' => auth()->user()->id,
                'plan_id' => $plan->id,
                'billing_cycle' => $this->billing_cycle_selected
            ],
            'mode' => 'subscription',
            'success_url' => url('subscription/welcome'),
            'cancel_url' => url('settings/subscription'),
        ]);

        return $checkout_session->url;
    }
    public function render()
    {
        return view('wave::livewire.billing.checkout', [
            'plans' => Plan::where('active', 1)->get()
        ]);
    }
}