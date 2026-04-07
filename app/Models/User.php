<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Tenant\AttachmentFile;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $guarded = ['id'];

    public function role(): BelongsTo
    {
        if (app()->bound(\Stancl\Tenancy\Contracts\Tenant::class)) {
            return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
        }
        return $this->belongsTo(PlatformRole::class, 'role_id');
    }

    public function staff(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\Tenant\Staff::class, 'user_id');
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\Tenant\Student::class, 'user_id');
    }

    public function parentProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\Tenant\ParentUser::class, 'user_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'two_factor_secret' => 'encrypted',
            'two_factor_confirmed_at' => 'datetime',
            'deactivated_at' => 'datetime',
        ];
    }


    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function hasEnabledTwoFactorAuthentication(): bool
    {
        return $this->two_factor_secret !== null && $this->two_factor_confirmed_at !== null;
    }

    public function isPlatformAdmin(): bool
    {
        return $this->role?->name === 'PlatformAdmin';
    }

    public function isPlatformStaff(): bool
    {
        return in_array($this->role?->name, ['PlatformAdmin', 'Staff']);
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(AttachmentFile::class, 'thumbnail_id');
    }

    public function original(): BelongsTo
    {
        return $this->belongsTo(AttachmentFile::class, 'original_id');
    }
}
