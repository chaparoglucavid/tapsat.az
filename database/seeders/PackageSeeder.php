<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            'VIP',
            'Premium'
        ];

        $languages = Language::IsActive()->pluck('code')->toArray();



        foreach ($array as $item) {

            $itemTranslations = [];
            foreach ($languages as $language) {
                $itemTranslations[$language] = $item;
            }

            Package::create([
                'uuid' => Str::uuid()->toString(),
                'name' => $itemTranslations,
                'is_active' => true,
                'duration_days' => 15
            ]);
        }
    }
}
