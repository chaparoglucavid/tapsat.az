<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@tapsat.az',
            'phone_number' => '0508221300',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'type' => UserType::ADMIN,
        ]);

        $this->call([
            LanguagesSeeder::class,
            CitiesSeeder::class,
            RegionsSeeder::class,
            CategoriesSeeder::class,
        ]);
    }
}
