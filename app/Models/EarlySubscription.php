<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlySubscription extends Model
{
    use HasFactory;

    protected $fillable = ['email'];
    protected $table = 'early_subscriptions';
}
