<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\SmsServiceInterface;
use App\Models\SmsLog;

class LogSmsService implements SmsServiceInterface
{
    public function send(string $to, string $message, array $context = []): void
    {
        SmsLog::query()->create([
            'to' => $to,
            'message' => $message,
            'context' => $context['channel'] ?? 'log',
            'meta' => $context,
        ]);
    }
}
