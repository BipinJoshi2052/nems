<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\StorageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class LocalStorageService implements StorageServiceInterface
{
    private const ORIGINAL_MAX = 1920;

    private const ORIGINAL_QUALITY = 85;

    private const THUMB_MAX = 300;

    private const THUMB_QUALITY = 80;

    public function storeImageWithVariants(UploadedFile $file, string $directory): array
    {
        $directory = trim($directory, '/');
        $base = Str::uuid()->toString();

        $originalRelative = $directory.'/'.$base.'_original.jpg';
        $thumbRelative = $directory.'/'.$base.'_thumb.jpg';

        $manager = ImageManager::gd();
        $path = $file->getRealPath();
        if ($path === false) {
            throw new \RuntimeException('Could not read uploaded file.');
        }

        $original = $manager->read($path);
        $original->scaleDown(width: self::ORIGINAL_MAX, height: self::ORIGINAL_MAX);

        $thumb = $manager->read($path);
        $thumb->scaleDown(width: self::THUMB_MAX, height: self::THUMB_MAX);

        $disk = Storage::disk('public');

        $disk->put($originalRelative, $original->toJpeg(self::ORIGINAL_QUALITY)->toString());
        $disk->put($thumbRelative, $thumb->toJpeg(self::THUMB_QUALITY)->toString());

        return [
            'original' => $originalRelative,
            'thumbnail' => $thumbRelative,
        ];
    }

    public function delete(string ...$paths): void
    {
        $disk = Storage::disk('public');
        foreach ($paths as $path) {
            if ($path !== '' && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }
}
