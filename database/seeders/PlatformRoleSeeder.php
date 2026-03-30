<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'PlatformAdmin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Staff', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tenant', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('platform_roles')->insertOrIgnore($roles);
    }
}
