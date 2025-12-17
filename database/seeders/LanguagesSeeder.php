<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::create(
            [
                'uuid' => Str::uuid()->toString(),
                'code' => 'az',
                'name' => 'Azərbaycan dili',
                'is_active' => true,
                'is_default' => true
            ]);

        Language::create(
            [
                'uuid' => Str::uuid()->toString(),
                'code' => 'en',
                'name' => 'English',
                'is_active' => true,
                'is_default' => false
            ]);
    }

}
