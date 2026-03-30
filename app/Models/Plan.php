<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'vertical',
        'price_monthly',
        'max_students',
        'storage_gb',
        'trial_days',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'max_students' => 'integer',
        'storage_gb' => 'integer',
        'trial_days' => 'integer',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
