<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'name' => 'Basic Montessori',
                'vertical' => 'montessori',
                'price_monthly' => 5000.00,
                'max_students' => 500,
                'storage_gb' => 10,
                'trial_days' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Montessori',
                'vertical' => 'montessori',
                'price_monthly' => 12000.00,
                'max_students' => 10000,
                'storage_gb' => 100,
                'trial_days' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'School Starter',
                'vertical' => 'school',
                'price_monthly' => 8000.00,
                'max_students' => 1000,
                'storage_gb' => 20,
                'trial_days' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
