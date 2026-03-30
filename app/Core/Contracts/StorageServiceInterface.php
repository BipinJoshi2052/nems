<?php

declare(strict_types=1);

namespace App\Core\Contracts;

use Illuminate\Http\UploadedFile;

interface StorageServiceInterface
{
    /**
     * Store an image and generate original (max 1920px, 85% JPEG quality when applicable) and thumbnail (max 300px, 80%).
     *
     * @return array{original: string, thumbnail: string}
     */
    public function storeImageWithVariants(UploadedFile $file, string $directory): array;

    public function delete(string ...$paths): void;
}
