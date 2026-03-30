<?php

declare(strict_types=1);

namespace App\Core\Contracts;

interface PushNotificationServiceInterface
{
    /**
     * @param  array<string, mixed>  $payload
     * @param  list<string|int>|array<string, mixed>  $recipients
     */
    public function send(array $payload, array $recipients = []): void;
}
