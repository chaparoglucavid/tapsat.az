<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\City;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bakuDistricts = [
            'Binəqədi',
            'Xətai',
            'Xəzər',
            'Nərimanov',
            'Nəsimi',
            'Nizami',
            'Pirallahı',
            'Sabunçu',
            'Səbail',
            'Suraxanı',
            'Yasamal',
            'Qaradağ',
        ];

        $languages = Language::IsActive()->pluck('code')->toArray();
        $baku = City::all()
            ->first(fn ($city) => $city->getTranslation('name', 'az') === 'Bakı');

            $baku_uuid = $baku->uuid;

        foreach ($bakuDistricts as $region) {

            $regionTranslations = [];
            foreach ($languages as $language) {
                $regionTranslations[$language] = $region;
            }

            Region::create([
                'uuid' => Str::uuid()->toString(),
                'city_uuid' => $baku_uuid,
                'slug' => Str::slug($region),
                'name' => $regionTranslations,
            ]);
        }
    }
}
