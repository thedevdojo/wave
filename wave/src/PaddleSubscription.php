<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class PaddleSubscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subscription_id',
        'plan_id',
        'user_id',
        'status',
        'next_bill_date',
        'update_url',
        'cancel_url',
        'cancelled_at',
        'last_payment_at',
        'next_payment_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cancelled_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'next_payment_at' => 'datetime',
    ];
}
