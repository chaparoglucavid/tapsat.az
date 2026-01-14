<?php

namespace Database\Seeders;

use App\Models\ComplaintSubject;
use Illuminate\Database\Seeder;

class ComplaintSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'Satilib',
            'Deleduz',
            'Kontaktlar yalnis gosterilib',
            'Qiymet yalnis gosterilib',
            'Saxta elan',
            'Sekiller yalnisdir',
            'Diger',
        ];

        foreach ($subjects as $name) {
            ComplaintSubject::firstOrCreate(['name' => $name]);
        }
    }
}

