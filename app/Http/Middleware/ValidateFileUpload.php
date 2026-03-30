<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Support\FileTypeValidator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateFileUpload
{
    public function handle(Request $request, Closure $next): Response
    {
        $file = $request->file('file') ?? $request->file('attachment');

        if ($file === null) {
            return $next($request);
        }

        if ($file->getSize() > FileTypeValidator::maxBytes()) {
            return response()->json(['message' => 'File too large.'], 422);
        }

        if (! FileTypeValidator::matchesMimeAndContent($file)) {
            return response()->json(['message' => 'Invalid file type or corrupted upload.'], 422);
        }

        return $next($request);
    }
}
