<?php

declare(strict_types=1);

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;

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
