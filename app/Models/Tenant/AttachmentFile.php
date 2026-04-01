<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AttachmentFile extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $appends = ['url'];

    public function getUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        $disk = ($this->disk === 'local' || !$this->disk) ? 'public' : $this->disk;
        return Storage::disk($disk)->url($this->path);
    }
}
