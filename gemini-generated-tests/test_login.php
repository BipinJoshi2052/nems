<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    tenancy()->initialize('f0e35e64-e33f-48c4-82f4-470895aa7399');

    $email = 'testschool@gmail.com';
    $password = 'password';

    $user = DB::table('users')->where('email', $email)->first();

    if (!$user) {
        echo "User not found in tenant database.\n";
        exit;
    }

    echo "Email: " . $email . "\n";
    echo "Hash from DB: " . $user->password . "\n";

    $check = Hash::check($password, $user->password);
    echo "Hash::check result: " . ($check ? "PASS" : "FAIL") . "\n";

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
