<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class PaddleSubscription extends Model
{
    protected $fillable = ['subscription_id', 'plan_id', 'user_id', 'status', 'update_url', 'cancel_url', 'cancelled_at', 'last_payment_at', 'next_payment_at'];

    protected $casts = [
        'last_payment_at' => 'datetime',
        'next_payment_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];
}
