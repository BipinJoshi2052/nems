<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tenants = \App\Models\Tenant::all();
foreach ($tenants as $tenant) {
    echo "Checking migration status for tenant: " . $tenant->id . "\n";
    tenancy()->initialize($tenant);
    $migrations = DB::table('migrations')->pluck('migration')->toArray();
    echo "Migrations run: " . implode(', ', $migrations) . "\n\n";
}
