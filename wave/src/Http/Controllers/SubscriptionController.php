<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
    private $paddle_url;

    private $api_key;

    public function __construct()
    {
        $this->api_key = config('wave.paddle.api_key');

        $this->paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
    }

    public function invoice(Request $request, $transactionId): RedirectResponse
    {
        // Only invoices for one of the user's own subscriptions
        $transaction = Http::withToken($this->api_key)->get($this->paddle_url.'/transactions/'.rawurlencode($transactionId));
        $subscriptionId = $transaction->successful() ? $transaction->json('data.subscription_id') : null;

        abort_unless(
            $subscriptionId && $request->user()->subscriptions()->where('vendor_slug', 'paddle')->where('vendor_subscription_id', $subscriptionId)->exists(),
            404
        );

        $response = Http::withToken($this->api_key)->get($this->paddle_url.'/transactions/'.rawurlencode($transactionId).'/invoice');
        $invoice = json_decode($response->body());

        // redirect user to the invoice download URL
        return redirect()->to($invoice->data->url);
    }
}
