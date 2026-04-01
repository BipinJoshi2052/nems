<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TenantRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'school_admin',
            'staff',
            'teacher',
            'student',
            'parent',
            'accountant',
            'receptionist'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
