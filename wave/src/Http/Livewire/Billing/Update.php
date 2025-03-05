<?php

namespace Wave\Http\Livewire\Billing;

use Wave\Plan;
use Wave\Subscription;
use Livewire\Component;
use Stripe\StripeClient;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use \Wave\Actions\Billing\Paddle\AddSubscriptionIdFromTransaction;

class Update extends Component
{
    public $update_url;
    public $cancel_url;
    public $paddle_url;

    public $cancellation_scheduled = false;
    public $subscription_ends_at;

    public $error_retrieving_data = false;

    public $subscription;

    public function mount(){
        $this->subscription = auth()->user()->subscription;
        
        if(config('wave.billing_provider') == 'paddle' && auth()->user()->subscriber()){
            $subscription = $this->subscription;

            if(is_null($this->subscription->vendor_subscription_id)){
                // If we did not obtain the user subscription id, try to get it again.
                $subscription = app(AddSubscriptionIdFromTransaction::class)($this->subscription->vendor_transaction_id);
                if(is_null($subscription)){
                    $this->error_retrieving_data = true;
                    return;
                }
            }

            $this->paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
            
            if(isset($subscription->id)){
                try {
                    $response = Http::withToken( config('wave.paddle.api_key') )->get($this->paddle_url . '/subscriptions/' . $subscription->vendor_subscription_id, []);
                    $paddle_subscription = json_decode($response->body());
                    $paddle_subscription = $paddle_subscription->data;
                } catch (\Exception $e) {
                    $this->error_retrieving_data = true;
                    return;
                }
            
                
                if(isset($paddle_subscription->scheduled_change->action) && $paddle_subscription->scheduled_change->action == 'cancel'){
                    $this->cancellation_scheduled = true;
                }

                $this->subscription_ends_at = $paddle_subscription->current_billing_period->ends_at;

                $this->cancel_url = $paddle_subscription->management_urls->cancel;
                $this->update_url = $paddle_subscription->management_urls->update_payment_method;
            }
        } elseif (config('wave.billing_provider') == 'stripe') {
            // Correctly fetch Stripe's `ends_at`
            $this->subscription_ends_at = optional($this->subscription)->ends_at;
        }
    }
    
    public function cancel(){

        $subscription = auth()->user()->latestSubscription();
        $response = Http::withToken( config('wave.paddle.api_key') )->post($this->paddle_url . '/subscriptions/' . $subscription->vendor_subscription_id . '/cancel', [
            'reason' => 'Customer requested cancellation'
        ]);

        if($response->successful()){
            $this->cancellation_scheduled = true;

            $responseObject = json_decode($response->body());
            $subscription->ends_at = $responseObject->data->current_billing_period->ends_at;
            $subscription->save();

            $this->js("window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'cancel-modal' }}));");
            Notification::make()
                ->title('Cancellation scheduled.')
                ->success()
                ->send(); 
        } 
    }

    public function cancelImmediately(){
        $subscription = auth()->user()->subscription;

        $response = Http::withToken( config('wave.paddle.api_key') )->post($this->paddle_url . '/subscriptions/' . $subscription->vendor_subscription_id . '/cancel', [
            'effective_from' => 'immediately'
        ]);

        if($response->successful()){
            $subscription->cancel();
            return redirect('/settings/subscription');
        }
    }

    public function render()
    {
        return view('wave::livewire.billing.update');
    }
}
