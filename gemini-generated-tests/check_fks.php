<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function getFKs($tableName) {
    return DB::select("SELECT conname FROM pg_constraint WHERE conrelid = '$tableName'::regclass AND contype = 'f'");
}

echo "FKs for platform_tenant_users:\n";
print_r(getFKs('platform_tenant_users'));

echo "FKs for platform_audit_logs:\n";
print_r(getFKs('platform_audit_logs'));

echo "FKs for sessions:\n";
print_r(getFKs('sessions'));
