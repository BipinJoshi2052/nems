<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$constraints = DB::select("
    SELECT 
        conname, 
        contype 
    FROM 
        pg_constraint 
    WHERE 
        conrelid = 'users'::regclass
");

echo "Constraints on users:\n";
print_r($constraints);

$indices = DB::select("
    SELECT 
        indexname 
    FROM 
        pg_indexes 
    WHERE 
        tablename = 'users'
");

echo "\nIndices on users:\n";
print_r($indices);
