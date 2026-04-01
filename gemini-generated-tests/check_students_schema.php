<?php

use App\Models\Tenant\Student;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$output = "";

$tenants = \App\Models\Tenant::all();
foreach ($tenants as $tenant) {
    $output .= "Checking tenant: " . $tenant->id . " (" . $tenant->domain . ")\n";
    try {
        tenancy()->initialize($tenant);
        
        if (Schema::hasTable('students')) {
            $columns = Schema::getColumnListing('students');
            $output .= "Columns in 'students': " . implode(', ', $columns) . "\n";
        } else {
            $output .= "Table 'students' DOES NOT EXIST!\n";
        }
    } catch (\Exception $e) {
        $output .= "Error for tenant " . $tenant->id . ": " . $e->getMessage() . "\n";
    }
}

file_put_contents('schema_check_output.txt', $output);
echo "Output saved to schema_check_output.txt\n";
