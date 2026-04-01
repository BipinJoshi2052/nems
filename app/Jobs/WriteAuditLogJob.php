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
        public ?string $userId,
        public string $action,
        public ?string $subjectType,
        public ?string $subjectId,
        public ?array $oldValues,
        public ?array $newValues,
        public ?string $ip,
        public ?string $dbConnection = null,
    ) {}

    public function handle(): void
    {
        // Set connection if provided, otherwise detect
        $conn = $this->dbConnection;
        
        if (!$conn) {
            $conn = app()->bound('tenant') ? 'tenant' : config('database.default');
        }

        if ($conn === 'tenant') {
            AuditLog::on('tenant')->create([
                'user_id' => $this->userId,
                'action' => $this->action,
                'subject_type' => $this->subjectType,
                'subject_id' => $this->subjectId,
                'old_values' => $this->oldValues,
                'new_values' => $this->newValues,
                'ip' => $this->ip,
            ]);
        } else {
            \App\Models\PlatformAuditLog::create([
                'admin_id' => $this->userId,
                'action' => $this->action,
                'subject_type' => $this->subjectType,
                'subject_id' => $this->subjectId,
                'old_values' => $this->oldValues,
                'new_values' => $this->newValues,
                'ip' => $this->ip,
            ]);
        }
    }
}
