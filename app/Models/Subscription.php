<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'expires_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isExpired(): bool
    {
        if ($this->status === 'active' && $this->expires_at) {
            return $this->expires_at->isPast();
        }

        if ($this->status === 'trial' && $this->trial_ends_at) {
            return $this->trial_ends_at->isPast();
        }

        return false;
    }

    public function isActive(): bool
    {
        return ($this->status === 'active' || $this->status === 'trial') && !$this->isExpired();
    }
}
