<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;

$tenantId = 'f0e35e64-e33f-48c4-82f4-470895aa7399';
$tenant = \App\Models\Tenant::find($tenantId);

if (!$tenant) {
    die("Tenant $tenantId not found\n");
}

Tenancy::initialize($tenant);

try {
    DB::beginTransaction();

    // 1. Change column types for audit_logs
    $tables = [
        'audit_logs' => ['user_id', 'subject_id']
    ];

    foreach ($tables as $table => $cols) {
        foreach ($cols as $col) {
            $type = DB::selectOne("SELECT data_type FROM information_schema.columns WHERE table_name = ? AND column_name = ?", [$table, $col]);
            if ($type && $type->data_type != 'character varying') {
                echo "Converting $table.$col from " . $type->data_type . " to VARCHAR(255)\n";
                DB::statement("ALTER TABLE $table ALTER COLUMN $col TYPE VARCHAR(255) USING $col::text");
            } else {
                echo "Skipping $table.$col, type is already " . ($type->data_type ?? 'NONE') . "\n";
            }
        }
    }

    DB::commit();
    echo "Tenant migration successful!\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "Tenant migration failed: " . $e->getMessage() . "\n";
}
