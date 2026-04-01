<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $id = DB::table('users')->value('id');
    echo "ID value: " . $id . " (Type: " . gettype($id) . ")\n";

    $schema = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'users' AND column_name = 'id'");
    echo "Schema info:\n";
    print_r($schema);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
