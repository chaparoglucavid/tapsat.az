<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            "Bakı",
            "Gəncə",
            "Sumqayıt",
            "Mingəçevir",
            "Lənkəran",
            "Şirvan",
            "Yevlax",
            "Naxçıvan",
            "Şəki",
            "Quba",
            "Qusar",
            "Saatlı",
            "Bərdə",
            "Sabirabad",
            "Salyan",
            "Şamaxı",
            "Biləsuvar",
            "Cəlilabad",
            "Astara",
            "Zaqatala",
            "Tovuz",
            "Qazax",
            "Gədəbəy",
            "Balakən",
            "Xaçmaz",
            "Lerik",
            "Göyçay",
            "Hacıqabul",
            "İsmayıllı",
            "Oğuz",
            "Şəmkir",
            "Füzuli",
            "Qəbələ",
            "Masallı",
            "Ağstafa",
            "Ağdaş",
            "Ağcabədi",
            "Beyləqan",
            "Bakıxanov",
            "Qax",
            "Qobustan",
            "Yardımlı",
            "Xırdalan",
            "Xızı",
            "Şabran",
            "Siyəzən",
            "Sədərək",
            "Ucar",
            "Cəbrayıl",
            "Zərdab",
            "Xocalı",
            "Xocavənd",
            "Laçın",
            "Kəlbəcər",
            "Qubadlı",
        ];

        $languages = Language::IsActive()->pluck('code')->toArray();



        foreach ($cities as $city) {

            $cityTranslations = [];
            foreach ($languages as $language) {
                $cityTranslations[$language] = $city;
            }

            City::create([
                'uuid' => Str::uuid()->toString(),
                'slug' => Str::slug($city),
                'name' => $cityTranslations,
            ]);
        }
    }
}
