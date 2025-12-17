<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            [
                'key'    => 'login_title',
                'group'  => 'auth',
                'values' => [
                    'az' => 'Sistemə daxil olun',
                    'en' => 'Login to system',
                ],
            ],
            [
                'key'    => 'dear',
                'group'  => 'dashboard',
                'values' => [
                    'az' => 'Hörmətli',
                    'en' => 'Dear',
                ],
            ],
            [
                'key'    => 'please_keep_your_password_hidden_for_system_security',
                'group'  => 'dashboard',
                'values' => [
                    'az' => 'Sistemin təhlükəsizliyi üçün zəhmət olmasa şifrənizi gizli saxlayın',
                    'en' => 'Please keep your password hidden for system security',
                ],
            ],
        ];

        foreach ($translations as $item) {
            foreach ($item['values'] as $locale => $value) {
                Translation::firstOrCreate([
                    'key'    => $item['key'],
                    'group'  => $item['group'],
                    'value' => $value,
                ],[
                    'uuid'   => (string) Str::uuid(),
                    'key'    => $item['key'],
                    'group'  => $item['group'],
                    'locale' => $locale,
                    'value'  => $value,
                ]);
            }
        }
    }
}
