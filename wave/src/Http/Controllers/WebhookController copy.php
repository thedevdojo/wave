<?php

namespace Wave\Http\Controllers;

use \App\Http\Requests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Cashier\Http\Controllers\WebhookController as StripeWebhookController;

class WebhookController extends StripeWebhookController
{

    public function handle(Request $request) {
        $this->handleWebhook($request);
    }

}
