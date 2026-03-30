<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PlatformAdminSeeder extends Seeder
{
    public function run(): void
    {
        $roleId = DB::table('platform_roles')->where('name', 'PlatformAdmin')->value('id');

        User::query()->updateOrCreate(
            ['email' => 'admin@school.test'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('ChangeMe!123'),
                'role_id' => $roleId,
            ]
        );
    }
}
