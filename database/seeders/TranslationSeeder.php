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
            // Validation
            ['locale' => 'en', 'group' => 'validation', 'key' => 'required', 'value' => 'The :attribute field is required.'],
            ['locale' => 'ne', 'group' => 'validation', 'key' => 'required', 'value' => ':attribute आवश्यक छ।'],
            ['locale' => 'en', 'group' => 'validation', 'key' => 'email', 'value' => 'The :attribute must be a valid email address.'],
            ['locale' => 'ne', 'group' => 'validation', 'key' => 'email', 'value' => ':attribute वैध इमेल ठेगाना हुनुपर्छ।'],
            
            // Common UI
            ['locale' => 'en', 'group' => 'common', 'key' => 'save', 'value' => 'Save'],
            ['locale' => 'ne', 'group' => 'common', 'key' => 'save', 'value' => 'बचत गर्नुहोस्'],
            ['locale' => 'en', 'group' => 'common', 'key' => 'cancel', 'value' => 'Cancel'],
            ['locale' => 'ne', 'group' => 'common', 'key' => 'cancel', 'value' => 'रद्द गर्नुहोस्'],
            ['locale' => 'en', 'group' => 'common', 'key' => 'delete', 'value' => 'Delete'],
            ['locale' => 'ne', 'group' => 'common', 'key' => 'delete', 'value' => 'मेट्नुहोस्'],
            ['locale' => 'en', 'group' => 'common', 'key' => 'confirm', 'value' => 'Confirm'],
            ['locale' => 'ne', 'group' => 'common', 'key' => 'confirm', 'value' => 'पुष्टि गर्नुहोस्'],
            ['locale' => 'en', 'group' => 'common', 'key' => 'edit', 'value' => 'Edit'],
            ['locale' => 'ne', 'group' => 'common', 'key' => 'edit', 'value' => 'सम्पादन गर्नुहोस्'],
            ['locale' => 'en', 'group' => 'common', 'key' => 'search', 'value' => 'Search'],
            ['locale' => 'ne', 'group' => 'common', 'key' => 'search', 'value' => 'खोज्नुहोस्'],
            
            // Status Labels
            ['locale' => 'en', 'group' => 'status', 'key' => 'active', 'value' => 'Active'],
            ['locale' => 'ne', 'group' => 'status', 'key' => 'active', 'value' => 'सक्रिय'],
            ['locale' => 'en', 'group' => 'status', 'key' => 'inactive', 'value' => 'Inactive'],
            ['locale' => 'ne', 'group' => 'status', 'key' => 'inactive', 'value' => 'निष्क्रिय'],
            ['locale' => 'en', 'group' => 'status', 'key' => 'pending', 'value' => 'Pending'],
            ['locale' => 'ne', 'group' => 'status', 'key' => 'pending', 'value' => 'पेन्डिङ'],
            
            // Mail Subjects
            ['locale' => 'en', 'group' => 'mail', 'key' => 'subjects.welcome', 'value' => 'Welcome to :app'],
            ['locale' => 'ne', 'group' => 'mail', 'key' => 'subjects.welcome', 'value' => ':app मा स्वागत छ'],
            ['locale' => 'en', 'group' => 'mail', 'key' => 'subjects.password_reset', 'value' => 'Reset your password'],
            ['locale' => 'ne', 'group' => 'mail', 'key' => 'subjects.password_reset', 'value' => 'पासवर्ड रिसेट गर्नुहोस्'],

            // Menu
            ['locale' => 'en', 'group' => 'menu', 'key' => 'title', 'value' => 'Menu'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'title', 'value' => 'मेनु'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'dashboard', 'value' => 'Dashboard'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'dashboard', 'value' => 'ड्यासबोर्ड'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'calendar', 'value' => 'Calendar'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'calendar', 'value' => 'पात्रो'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'user_profile', 'value' => 'User Profile'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'user_profile', 'value' => 'प्रयोगकर्ता प्रोफाइल'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'finance', 'value' => 'Finance'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'finance', 'value' => 'वित्त'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'attendance', 'value' => 'Attendance'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'attendance', 'value' => 'हाजिरी'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'user_management', 'value' => 'User Management'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'user_management', 'value' => 'प्रयोगकर्ता व्यवस्थापन'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'staff', 'value' => 'Staff'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'staff', 'value' => 'कर्मचारी'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'students', 'value' => 'Students'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'students', 'value' => 'विद्यार्थीहरू'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'parents', 'value' => 'Parents'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'parents', 'value' => 'अभिभावकहरू'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'settings', 'value' => 'Settings'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'settings', 'value' => 'सेटिङहरू'],
            ['locale' => 'en', 'group' => 'menu', 'key' => 'school_settings', 'value' => 'School Settings'],
            ['locale' => 'ne', 'group' => 'menu', 'key' => 'school_settings', 'value' => 'विद्यालय सेटिङहरू'],
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
