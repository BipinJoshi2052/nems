<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    protected $casts = [
        'date_of_birth_ad' => 'date',
        'withdrawn_at' => 'datetime',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(AttachmentFile::class, 'thumbnail_id');
    }

    public function original(): BelongsTo
    {
        return $this->belongsTo(AttachmentFile::class, 'original_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentUser::class, 'student_parent', 'student_id', 'parent_user_id')
            ->withPivot('relationship', 'is_primary_contact')
            ->withTimestamps();
    }
}
