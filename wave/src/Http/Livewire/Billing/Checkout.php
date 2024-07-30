<?php

namespace Wave\Http\Livewire\Billing;

use Livewire\Component;
use Wave\Plan;
use Stripe\StripeClient;

class Checkout extends Component
{
    public $billing_cycle_available = 'month'; // month, year, or both;
    public $billing_cycle_selected = 'month';

    public function mount()
    {
        $this->updateCycleBasedOnPlans();
    }

    public function redirectToPaymentProvider(Plan $plan)
    {
        $stripe = new StripeClient(config('devdojo.billing.keys.stripe.secret_key'));

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
            'cancel_url' => url('billing/checkout'),
        ]);

        return redirect($checkout_session->url);
    }

    public function updateCycleBasedOnPlans()
    {
        $plans = Plan::all();
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

    public function render()
    {
        return view('wave::livewire.billing.checkout', [
            'plans' => Plan::all()
        ]);
    }
}