<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tapCategories = [
            [
                'name' => 'Daşınmaz əmlak',
                'slug' => 'real-estate',
                'children' => [
                    ['name' => 'Mənzillər', 'slug' => 'apartments'],
                    ['name' => 'Evlər / Villalar', 'slug' => 'houses'],
                    ['name' => 'Torpaq', 'slug' => 'land'],
                    ['name' => 'Qarajlar', 'slug' => 'garages'],
                    ['name' => 'Obyektlər və ofislər', 'slug' => 'commercial'],
                    ['name' => 'Günlük kirayə', 'slug' => 'daily-rent'],
                ],
            ],

            [
                'name' => 'Nəqliyyat',
                'slug' => 'transport',
                'children' => [
                    ['name' => 'Avtomobillər', 'slug' => 'cars'],
                    ['name' => 'Motosikletlər', 'slug' => 'motorcycles'],
                    ['name' => 'Avtobuslar və yük maşınları', 'slug' => 'trucks'],
                    ['name' => 'Ehtiyat hissələri', 'slug' => 'auto-parts'],
                    ['name' => 'Avtoaksesuarlar', 'slug' => 'auto-accessories'],
                ],
            ],

            [
                'name' => 'Elektronika',
                'slug' => 'electronics',
                'children' => [
                    ['name' => 'Telefonlar', 'slug' => 'phones'],
                    ['name' => 'Kompüterlər', 'slug' => 'computers'],
                    ['name' => 'Noutbuklar', 'slug' => 'laptops'],
                    ['name' => 'Televizorlar', 'slug' => 'tvs'],
                    ['name' => 'Məişət texnikası', 'slug' => 'home-appliances'],
                    ['name' => 'Foto və video texnika', 'slug' => 'photo-video'],
                ],
            ],

            [
                'name' => 'Ev və bağ',
                'slug' => 'home-garden',
                'children' => [
                    ['name' => 'Mebel', 'slug' => 'furniture'],
                    ['name' => 'Məişət əşyaları', 'slug' => 'household-items'],
                    ['name' => 'Bağ və bağçılıq', 'slug' => 'garden'],
                    ['name' => 'Tikinti materialları', 'slug' => 'construction-materials'],
                ],
            ],

            [
                'name' => 'Şəxsi əşyalar',
                'slug' => 'personal-items',
                'children' => [
                    ['name' => 'Geyim və ayaqqabı', 'slug' => 'clothes-shoes'],
                    ['name' => 'Aksesuarlar', 'slug' => 'accessories'],
                    ['name' => 'Saatlar və zinət əşyaları', 'slug' => 'watches-jewelry'],
                    ['name' => 'Parfümeriya', 'slug' => 'perfumes'],
                ],
            ],

            [
                'name' => 'Uşaq aləmi',
                'slug' => 'kids',
                'children' => [
                    ['name' => 'Uşaq geyimləri', 'slug' => 'kids-clothes'],
                    ['name' => 'Oyuncaqlar', 'slug' => 'toys'],
                    ['name' => 'Uşaq arabaları', 'slug' => 'strollers'],
                    ['name' => 'Məktəb ləvazimatları', 'slug' => 'school-items'],
                ],
            ],

            [
                'name' => 'Heyvanlar',
                'slug' => 'animals',
                'children' => [
                    ['name' => 'İtlər', 'slug' => 'dogs'],
                    ['name' => 'Pişiklər', 'slug' => 'cats'],
                    ['name' => 'Quşlar', 'slug' => 'birds'],
                    ['name' => 'Balıqlar', 'slug' => 'fish'],
                    ['name' => 'Heyvanlar üçün məhsullar', 'slug' => 'pet-products'],
                ],
            ],

            [
                'name' => 'Xidmətlər və biznes',
                'slug' => 'services-business',
                'children' => [
                    ['name' => 'Təmir və tikinti', 'slug' => 'repair-construction'],
                    ['name' => 'Təhsil və kurslar', 'slug' => 'education'],
                    ['name' => 'Gözəllik və sağlamlıq', 'slug' => 'beauty-health'],
                    ['name' => 'Nəqliyyat xidmətləri', 'slug' => 'transport-services'],
                    ['name' => 'Biznes üçün avadanlıq', 'slug' => 'business-equipment'],
                ],
            ],

            [
                'name' => 'İş elanları',
                'slug' => 'jobs',
                'children' => [
                    ['name' => 'Vakansiyalar', 'slug' => 'vacancies'],
                    ['name' => 'İş axtarıram', 'slug' => 'job-seeker'],
                ],
            ],

            [
                'name' => 'Asudə vaxt və hobbi',
                'slug' => 'hobby',
                'children' => [
                    ['name' => 'İdman və istirahət', 'slug' => 'sport'],
                    ['name' => 'Musiqi alətləri', 'slug' => 'music-instruments'],
                    ['name' => 'Kitablar və jurnallar', 'slug' => 'books'],
                    ['name' => 'Kolleksiya əşyaları', 'slug' => 'collectibles'],
                ],
            ],
        ];

        $languages = Language::where('is_active', true)
            ->pluck('code')
            ->toArray();

        foreach ($tapCategories as $parentCategory) {
            $parentTranslations = [];
            foreach ($languages as $lang) {
                $parentTranslations[$lang] = $parentCategory['name'];
            }
            $parent = Category::create([
                'uuid' => Str::uuid()->toString(),
                'parent_uuid' => null,
                'name' => $parentTranslations,
                'slug' => $parentCategory['slug'],
                'is_active' => true,
            ]);

            foreach ($parentCategory['children'] as $childCategory) {

                $childTranslations = [];
                foreach ($languages as $lang) {
                    $childTranslations[$lang] = $childCategory['name'];
                }

                Category::create([
                    'uuid' => Str::uuid()->toString(),
                    'parent_uuid' => $parent->uuid,
                    'name' => $childTranslations,
                    'slug' => $childCategory['slug'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
