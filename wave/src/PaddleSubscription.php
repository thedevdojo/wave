<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class PaddleSubscription extends Model
{
    protected $fillable = ['subscription_id', 'plan_id', 'user_id', 'status', 'next_bill_date', 'update_url', 'cancel_url', 'cancelled_at'];
}
