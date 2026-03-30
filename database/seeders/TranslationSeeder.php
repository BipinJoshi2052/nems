<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $pairs = [
            ['locale' => 'en', 'group' => 'validation', 'key' => 'required', 'value' => 'The :attribute field is required.'],
            ['locale' => 'ne', 'group' => 'validation', 'key' => 'required', 'value' => ':attribute आवश्यक छ।'],
            ['locale' => 'en', 'group' => 'common', 'key' => 'save', 'value' => 'Save'],
            ['locale' => 'ne', 'group' => 'common', 'key' => 'save', 'value' => 'बचत गर्नुहोस्'],
            ['locale' => 'en', 'group' => 'common', 'key' => 'cancel', 'value' => 'Cancel'],
            ['locale' => 'ne', 'group' => 'common', 'key' => 'cancel', 'value' => 'रद्द गर्नुहोस्'],
            ['locale' => 'en', 'group' => 'mail', 'key' => 'subjects.welcome', 'value' => 'Welcome to :app'],
            ['locale' => 'ne', 'group' => 'mail', 'key' => 'subjects.welcome', 'value' => ':app मा स्वागत छ'],
            ['locale' => 'en', 'group' => 'mail', 'key' => 'subjects.password_reset', 'value' => 'Reset your password'],
            ['locale' => 'ne', 'group' => 'mail', 'key' => 'subjects.password_reset', 'value' => 'पासवर्ड रिसेट गर्नुहोस्'],
        ];

        foreach ($pairs as $row) {
            Translation::query()->updateOrCreate(
                [
                    'locale' => $row['locale'],
                    'group' => $row['group'],
                    'key' => $row['key'],
                ],
                ['value' => $row['value']]
            );
        }
    }
}
