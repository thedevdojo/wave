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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'next_bill_date' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
}
