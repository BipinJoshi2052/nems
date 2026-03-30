<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class WriteAuditLogJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ?int $userId,
        public string $action,
        public ?string $subjectType,
        public ?int $subjectId,
        public ?array $oldValues,
        public ?array $newValues,
        public ?string $ip,
    ) {}

    public function handle(): void
    {
        AuditLog::query()->create([
            'user_id' => $this->userId,
            'action' => $this->action,
            'subject_type' => $this->subjectType,
            'subject_id' => $this->subjectId,
            'old_values' => $this->oldValues,
            'new_values' => $this->newValues,
            'ip' => $this->ip,
        ]);
    }
}
