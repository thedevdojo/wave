<?php

namespace Wave\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class Paddle extends Controller
{
    public function invoice($transactionId)
    {
        $paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';

        $response = Http::withToken(config('wave.paddle.api_key'))->get($paddle_url.'/transactions/'.$transactionId.'/invoice');
        $invoice = json_decode($response->body());

        // redirect user to the invoice download URL
        return redirect($invoice->data->url);

    }
}
