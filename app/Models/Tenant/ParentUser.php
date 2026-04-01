<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ParentUser extends Authenticatable
{
    use Notifiable, HasUuids, HasFactory;

    protected $table = 'parent_users';

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_parent', 'parent_user_id', 'student_id')
            ->withPivot('relationship', 'is_primary_contact')
            ->withTimestamps();
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
