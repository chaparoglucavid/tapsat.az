<?php

namespace Database\Seeders;

use App\Models\Translations;
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
        Translations::create(
            [
                'uuid' => Str::uuid()->toString(),
                'key' => 'login_title',
                'locale' => 'az',
                'group' => 'auth',
                'value' => 'Sistemə daxil olun'
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'key' => 'login_title',
                'locale' => 'en',
                'group' => 'auth',
                'value' => 'Login to system'
            ]
        );
    }
}
