<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PlatformTenantUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'platform_tenant_users';

    protected $fillable = [
        'tenant_id',
        'email',
        'password',
        'role',
        'language_preference',
    ];

    protected $hidden = [
        'password',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
