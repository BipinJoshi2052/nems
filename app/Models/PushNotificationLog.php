<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotificationLog extends Model
{
    protected $fillable = [
        'title',
        'body',
        'payload',
        'recipients',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'recipients' => 'array',
        ];
    }
}
