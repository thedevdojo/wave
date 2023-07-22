<?php

namespace Wave\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use Wave\PaddleSubscription;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Wave\Http\Middleware\VerifyWebhook;

class WebhookController extends Controller
{
    public function __construct()
    {
        if (config('wave.paddle.public_key')) {
            $this->middleware(VerifyWebhook::class);
        }
    }

    public function __invoke(Request $request)
    {
        $method = match ($request->get('alert_name', null)) {
            'subscription_cancelled',
            'subscription_payment_failed' => 'subscriptionCancelled',
            default => null,
        };

        if (method_exists($this, $method)) {
            try {
                $this->{$method}($request);
            } catch (\Exception $e) {
                return response('Webhook failed');
            }
        }

        return response('Webhook handled');
    }

    protected function subscriptionCancelled(Request $request)
    {
        $subscription = PaddleSubscription::where('subscription_id', $request->subscription_id)->firstOrFail();
        $subscription->cancelled_at = Carbon::now();
        $subscription->status = 'cancelled';
        $subscription->save();
        $user = config('wave.user_model')::find($subscription->user_id);
        $cancelledRole = Role::where('name', '=', 'cancelled')->first();
        $user->role_id = $cancelledRole->id;
        $user->save();
    }
}
