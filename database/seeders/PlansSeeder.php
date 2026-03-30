<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'vertical' => 'montessori',
                'price_monthly' => 999.00,
                'max_students' => 100,
                'storage_gb' => 2,
                'trial_days' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Growth',
                'vertical' => 'montessori',
                'price_monthly' => 2499.00,
                'max_students' => 500,
                'storage_gb' => 10,
                'trial_days' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Institution',
                'vertical' => 'montessori',
                'price_monthly' => 5999.00,
                'max_students' => 1000000, // Representing "unlimited"
                'storage_gb' => 50,
                'trial_days' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->updateOrInsert(
                ['name' => $plan['name'], 'vertical' => $plan['vertical']],
                $plan
            );
        }
    }
}
