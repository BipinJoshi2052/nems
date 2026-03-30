<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\StorageServiceInterface;
use App\Core\Support\FileTypeValidator;
use App\Enums\AttachmentFolderEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AttachmentService
{
    public function __construct(
        private readonly StorageServiceInterface $storage,
    ) {}

    /**
     * @return array{original: string, thumbnail: string|null}
     */
    public function upload(UploadedFile $file, AttachmentFolderEnum $folder, ?int $quotaBytesRemaining = null): array
    {
        if ($file->getSize() > FileTypeValidator::maxBytes()) {
            throw new InvalidArgumentException('File exceeds maximum size.');
        }

        if (! FileTypeValidator::matchesMimeAndContent($file)) {
            throw new InvalidArgumentException('File type is not allowed or content does not match.');
        }

        if ($quotaBytesRemaining !== null && $file->getSize() > $quotaBytesRemaining) {
            throw new InvalidArgumentException('Storage quota exceeded.');
        }

        $base = 'attachments/'.$folder->value;

        if (str_starts_with((string) $file->getMimeType(), 'image/')) {
            return $this->storage->storeImageWithVariants($file, $base);
        }

        $safe = FileTypeValidator::sanitizeFilename($file->getClientOriginalName());
        $name = Str::uuid()->toString().'_'.$safe;
        $relative = Storage::disk('local')->putFileAs($base, $file, $name);

        return [
            'original' => $relative,
            'thumbnail' => null,
        ];
    }

    public function delete(array $paths): void
    {
        $paths = array_filter($paths);
        if ($paths === []) {
            return;
        }

        $this->storage->delete(...$paths);
    }

    public function assertWithinQuota(int $usedBytes, int $quotaBytes): bool
    {
        return $usedBytes <= $quotaBytes;
    }
}
