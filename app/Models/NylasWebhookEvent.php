<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NylasWebhookEvent extends Model
{
    use HasFactory;

    protected $table = 'nylas_webhook_events';

    protected $fillable = [
        'event_type',
        'grant_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
