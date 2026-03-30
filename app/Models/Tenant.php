<?php

declare(strict_types=1);

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;

    /**
     * Tenant Status Constants (State Machine)
     */
    public const STATUS_PENDING_OTP      = 'pending_otp';
    public const STATUS_PENDING_PASSWORD = 'pending_password';
    public const STATUS_PROVISIONING      = 'provisioning';
    public const STATUS_TRIAL             = 'trial';
    public const STATUS_ACTIVE            = 'active';
    public const STATUS_EXPIRED           = 'expired';
    public const STATUS_SUSPENDED         = 'suspended';

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    /**
     * Enforce status transition rules.
     */
    public function transitionTo(string $newStatus): void
    {
        $allowed = [
            self::STATUS_PENDING_OTP      => [self::STATUS_PENDING_PASSWORD],
            self::STATUS_PENDING_PASSWORD => [self::STATUS_PROVISIONING],
            self::STATUS_PROVISIONING      => [self::STATUS_TRIAL],
            self::STATUS_TRIAL             => [self::STATUS_ACTIVE, self::STATUS_EXPIRED, self::STATUS_SUSPENDED],
            self::STATUS_ACTIVE            => [self::STATUS_EXPIRED, self::STATUS_SUSPENDED],
            self::STATUS_EXPIRED           => [self::STATUS_ACTIVE, self::STATUS_SUSPENDED],
            self::STATUS_SUSPENDED         => [self::STATUS_ACTIVE, self::STATUS_EXPIRED, self::STATUS_TRIAL],
        ];

        $currentStatus = $this->status ?? self::STATUS_PENDING_OTP;

        if (!isset($allowed[$currentStatus]) || !in_array($newStatus, $allowed[$currentStatus])) {
            throw new \InvalidArgumentException("Invalid status transition from {$currentStatus} to {$newStatus}");
        }

        $this->update(['status' => $newStatus]);
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'subdomain',
            'custom_domain',
            'status',
            'vertical',
            'database_name',
            'created_at',
            'updated_at',
            'data',
        ];
    }
}
