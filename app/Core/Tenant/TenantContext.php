<?php

declare(strict_types=1);

namespace App\Core\Tenant;

use App\Models\Tenant;

final class TenantContext
{
    private static ?Tenant $tenant = null;

    public static function set(?Tenant $tenant): void
    {
        self::$tenant = $tenant;
    }

    public static function get(): ?Tenant
    {
        return self::$tenant;
    }

    public static function clear(): void
    {
        self::$tenant = null;
    }
}
