<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;

class WebhookController extends Controller
{

	public function handleCustomerSubscriptionDeleted(array $payload)
    {

        // $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        // if ($user) {
        //     $user->subscriptions->filter(function ($subscription) use ($payload) {
        //         return $subscription->stripe_id === $payload['data']['object']['id'];
        //     })->each(function ($subscription) {
        //         $subscription->markAsCancelled();
        //     });

        //     $cancelled_id = \TCG\Voyager\Models\Role::where('name', '=', 'cancelled')->first()->id;
        //     $user->role_id = $cancelled_id;
        // 	$user->save();
        // }

	    // return new Response('Webhook Handled', 200);

    }

}
