<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
