<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Jobs\WriteAuditLogJob;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public function record(
        string $action,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null,
        ?string $ip = null,
    ): void {
        WriteAuditLogJob::dispatch(
            $userId ?? Auth::id(),
            $action,
            $subjectType,
            $subjectId,
            $oldValues,
            $newValues,
            $ip ?? request()?->ip(),
        );
    }
}
