<?php

declare(strict_types=1);

namespace App\Core\Contracts;

interface SmsServiceInterface
{
    public function send(string $to, string $message, array $context = []): void;
}
