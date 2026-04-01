<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$output = "";

$tenants = \App\Models\Tenant::all();
foreach ($tenants as $tenant) {
    $output .= "Checking tenant: " . $tenant->id . "\n";
    try {
        tenancy()->initialize($tenant);
        
        foreach (['students', 'parents', 'users'] as $table) {
            if (Schema::hasTable($table)) {
                $columns = Schema::getColumnListing($table);
                $output .= "Columns in '{$table}': " . implode(', ', $columns) . "\n";
            } else {
                $output .= "Table '{$table}' DOES NOT EXIST!\n";
            }
        }
    } catch (\Exception $e) {
        $output .= "Error for tenant " . $tenant->id . ": " . $e->getMessage() . "\n";
    }
}

file_put_contents('final_schema_check.txt', $output);
echo "Output saved to final_schema_check.txt\n";
