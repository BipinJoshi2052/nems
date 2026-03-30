<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\PushNotificationServiceInterface;
use App\Models\PushNotificationLog;

class LogPushService implements PushNotificationServiceInterface
{
    public function send(array $payload, array $recipients = []): void
    {
        PushNotificationLog::query()->create([
            'title' => $payload['title'] ?? null,
            'body' => $payload['body'] ?? null,
            'payload' => $payload,
            'recipients' => $recipients,
        ]);
    }
}
