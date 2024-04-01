<?php

namespace Wave\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use Wave\PaddleSubscription;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Wave\Http\Middleware\VerifyWebhook;
use Illuminate\Support\Facades\Log;

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
        $alertName = $request->get('event_type', null);
        $method = null;
    
        switch ($alertName) {
            case 'subscription_cancelled':
            case 'subscription.canceled':
            case 'subscription_payment_failed':
                $method = 'subscriptionCancelled';
                break;
            default:
                $method = null;
                break;
        }
    
        if ($method && method_exists($this, $method)) {
            try {
                $this->{$method}($request);
            } catch (\Exception $e) {
                Log::error("Webhook handling error: " . $e->getMessage());
                return response('Webhook handling failed', 500);
            }
        }
    
        return response('Webhook handled', 200);
    }
    

    protected function subscriptionCancelled(Request $request)
    {
        $subscriptionId = $request->input('data.id'); // Adjusted to match the payload structure
    
        // Ensure the subscription ID is provided
        if (is_null($subscriptionId)) {
            Log::warning('Subscription ID missing in subscriptionCancelled webhook.');
            return;
        }
    
        $subscription = PaddleSubscription::where('subscription_id', $subscriptionId)->first();
    
        if (!$subscription) {
            Log::warning("Subscription not found: {$subscriptionId}");
            return;
        }
    
        // Use the occurred_at field from the payload for the cancellation time
        $occurredAt = Carbon::parse($request->input('occurred_at', now()));
    
        $subscription->cancelled_at = $occurredAt;
        $subscription->status = 'cancelled';
        $subscription->save();
    
        $user = config('wave.user_model')::find($subscription->user_id);
    
        // Check if user exists
        if (!$user) {
            Log::warning("User not found: {$subscription->user_id}");
            return;
        }
    
        $cancelledRole = Role::where('name', '=', 'cancelled')->first();
    
        // Ensure the cancelled role exists
        if (!$cancelledRole) {
            Log::error('Cancelled role not found.');
            return;
        }
    
        $user->role_id = $cancelledRole->id;
        $user->save();
    }    
}
