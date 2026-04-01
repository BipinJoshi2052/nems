<?php

declare(strict_types=1);

namespace App\Http\Traits;

use App\Core\Services\AttachmentService;
use App\Enums\AttachmentFolderEnum;
use App\Enums\ImageVariantEnum;
use App\Models\Tenant\AttachmentFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;

trait HandlesAttachments
{
    protected function handlePhotoUpload(Model $model, UploadedFile $file, AttachmentFolderEnum $folder): void
    {
        $attachmentService = app(AttachmentService::class);
        
        $uploaded = $attachmentService->upload($file, $folder);

        // Delete old attachments if they exist
        if ($model->original_id || $model->thumbnail_id) {
            $this->deleteAttachments($model);
        }

        $original = AttachmentFile::create([
            'disk' => 'public',
            'attachment_type' => ImageVariantEnum::Original->value, // Original
            'path' => $uploaded['original'],
            'folder' => $folder->value,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
        ]);

        $thumbnailId = null;
        if ($uploaded['thumbnail']) {
            $thumbFile = AttachmentFile::create([
                'disk' => 'public',
                'attachment_type' => ImageVariantEnum::Thumbnail->value, // Thumbnail
                'path' => $uploaded['thumbnail'],
                'folder' => $folder->value,
                'mime_type' => 'image/jpeg', // LocalStorageService scales and converts
                'size' => 0, // Size is not easily available without reading back
                'original_name' => 'thumb_' . $file->getClientOriginalName(),
                'extension' => 'jpg',
            ]);
            $thumbnailId = $thumbFile->id;
        }

        $model->update([
            'original_id' => $original->id,
            'thumbnail_id' => $thumbnailId,
        ]);
    }

    protected function deleteAttachments(Model $model): void
    {
        $attachmentService = app(AttachmentService::class);
        
        $paths = [];
        if ($model->original) {
            $paths[] = $model->original->path;
            $model->original->delete();
        }
        if ($model->thumbnail) {
            $paths[] = $model->thumbnail->path;
            $model->thumbnail->delete();
        }

        if ($paths !== []) {
            $attachmentService->delete($paths);
        }

        $model->update([
            'original_id' => null,
            'thumbnail_id' => null,
        ]);
    }
}
