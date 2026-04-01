<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function run($sql) {
    echo "Running: $sql\n";
    try {
        DB::statement($sql);
    } catch (\Exception $e) {
        echo "Warning: " . $e->getMessage() . "\n";
    }
}

try {
    DB::beginTransaction();

    // 1. Drop constraints
    run('ALTER TABLE platform_audit_logs DROP CONSTRAINT IF EXISTS platform_audit_logs_admin_id_foreign');
    run('ALTER TABLE platform_tenant_users DROP CONSTRAINT IF EXISTS platform_tenant_users_user_id_foreign');
    run('ALTER TABLE users DROP CONSTRAINT IF EXISTS platform_admins_pkey CASCADE');
    run('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_pkey CASCADE');
    run('ALTER TABLE users DROP CONSTRAINT IF EXISTS pk_users_uuid CASCADE');
    
    run('DROP INDEX IF EXISTS platform_admins_pkey');
    run('DROP INDEX IF EXISTS users_pkey');
    run('DROP INDEX IF EXISTS pk_users_uuid');

    // 2. Change column types (only if not already correct)
    $tables = [
        'users' => ['id'],
        'platform_tenant_users' => ['user_id'],
        'platform_audit_logs' => ['admin_id', 'subject_id'],
        'sessions' => ['user_id']
    ];

    foreach ($tables as $table => $cols) {
        foreach ($cols as $col) {
            $type = DB::selectOne("SELECT data_type FROM information_schema.columns WHERE table_name = ? AND column_name = ?", [$table, $col]);
            if ($type && $type->data_type != 'character varying') {
                run("ALTER TABLE $table ALTER COLUMN $col TYPE VARCHAR(255) USING $col::text");
            } else {
                echo "Skipping $table.$col, type is already " . ($type->data_type ?? 'NONE') . "\n";
            }
        }
    }

    // 3. Restore Primary Key
    run('ALTER TABLE users ADD CONSTRAINT pk_users_uuid PRIMARY KEY (id)');

    // 4. Restore Foreign Keys
    run('ALTER TABLE platform_audit_logs ADD CONSTRAINT platform_audit_logs_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL');
    
    $hasCol = DB::selectOne("SELECT 1 FROM information_schema.columns WHERE table_name = 'platform_tenant_users' AND column_name = 'user_id'");
    if ($hasCol) {
        run('ALTER TABLE platform_tenant_users ADD CONSTRAINT platform_tenant_users_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
    }

    DB::commit();
    echo "Migration successful!\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "CRITICAL Migration failed: " . $e->getMessage() . "\n";
}
