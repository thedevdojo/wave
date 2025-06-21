<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class UserRegistered
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // $user = $event->user;
        // Perform any functionality to the user here...
    }
}
