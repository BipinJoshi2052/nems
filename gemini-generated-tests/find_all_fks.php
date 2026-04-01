<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$fks = DB::select("
    SELECT 
        tc.table_name, 
        kcu.column_name, 
        ccu.table_name AS foreign_table_name, 
        ccu.column_name AS foreign_column_name, 
        tc.constraint_name 
    FROM 
        information_schema.table_constraints AS tc 
        JOIN information_schema.key_column_usage AS kcu 
          ON tc.constraint_name = kcu.constraint_name 
          AND tc.table_schema = kcu.table_schema 
        JOIN information_schema.constraint_column_usage AS ccu 
          ON ccu.constraint_name = tc.constraint_name 
          AND ccu.table_schema = tc.table_schema 
    WHERE 
        tc.constraint_type = 'FOREIGN KEY' 
        AND ccu.table_name='users'
");

echo "Foreign Keys pointing to users:\n";
foreach ($fks as $fk) {
    echo "Table: " . $fk->table_name . ", Column: " . $fk->column_name . ", Constraint: " . $fk->constraint_name . "\n";
}
