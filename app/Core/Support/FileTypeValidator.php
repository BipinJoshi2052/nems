<?php

declare(strict_types=1);

namespace App\Core\Support;

use Illuminate\Http\UploadedFile;
use ZipArchive;

final class FileTypeValidator
{
    public const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public static function maxBytes(): int
    {
        return 20 * 1024 * 1024;
    }

    public static function matchesMimeAndContent(UploadedFile $file, ?string $expectedMime = null): bool
    {
        $mime = $expectedMime ?? $file->getMimeType();
        if (! in_array($mime, self::ALLOWED_MIMES, true)) {
            return false;
        }

        $path = $file->getRealPath();
        if ($path === false) {
            return false;
        }

        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return false;
        }

        $header = fread($handle, 12);
        fclose($handle);

        if ($header === false || strlen($header) < 4) {
            return false;
        }

        $hex = strtoupper(bin2hex($header));

        return match ($mime) {
            'image/jpeg' => str_starts_with($hex, 'FFD8FF'),
            'image/png' => str_starts_with($hex, '89504E470D0A1A0A'),
            'image/gif' => str_starts_with($hex, '47494638'),
            'image/webp' => self::isWebp($path),
            'application/pdf' => str_starts_with($hex, '25504446'),
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => self::isDocxZip($path),
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => self::isXlsxZip($path),
            default => false,
        };
    }

    private static function isWebp(string $path): bool
    {
        $head = file_get_contents($path, false, null, 0, 16);
        if ($head === false || strlen($head) < 12) {
            return false;
        }

        return str_starts_with(strtoupper(bin2hex(substr($head, 0, 4))), '52494646')
            && substr($head, 8, 4) === 'WEBP';
    }

    private static function isDocxZip(string $path): bool
    {
        return self::zipHas($path, 'word/document.xml');
    }

    private static function isXlsxZip(string $path): bool
    {
        return self::zipHas($path, 'xl/workbook.xml');
    }

    private static function zipHas(string $path, string $entry): bool
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return false;
        }

        $ok = $zip->locateName($entry) !== false;
        $zip->close();

        return $ok;
    }

    public static function sanitizeFilename(string $name): string
    {
        $name = basename($name);
        $name = preg_replace('/[^a-zA-Z0-9._-]+/', '_', $name) ?? '';

        return $name !== '' ? $name : 'file';
    }
}
