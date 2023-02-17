<?php

namespace Wave\Http\Livewire\Settings;

use Livewire\Component;
use Wave\Http\Controllers\SubscriptionController;

class Subscription extends Component
{

    protected $listeners = ['checkoutCancel'];

    public function checkoutCancel($payload){

        // Create a new form request to send to the subscription controller
        $request = new \Illuminate\Http\Request();
        $request->setMethod('POST');
        $request->request->add(['id' => $payload['id']]);

        $subscription = new SubscriptionController;
        $cancelResponse = $subscription->cancel($request);
        $this->dispatchBrowserEvent('checkoutCancelResponse', $cancelResponse->getData(true) );
    }

    public function render()
    {
        return view('theme::livewire.settings.subscription');
    }
}
