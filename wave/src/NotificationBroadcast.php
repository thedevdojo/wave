<?php

namespace Wave;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class NotificationBroadcast extends Model
{
    use HasUuids;

    protected $fillable = ['id', 'title', 'category', 'body', 'sent_at'];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime'];
    }

    public static function categories(): array
    {
        return ['security' => 'Security', 'marketing' => 'Marketing', 'product_update' => 'Product update'];
    }
}
