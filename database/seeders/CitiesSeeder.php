<?php

namespace Database\Seeders;

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
            "Füzuli"
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'uuid' => Str::uuid()->toString(),
                'name' => $city,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
