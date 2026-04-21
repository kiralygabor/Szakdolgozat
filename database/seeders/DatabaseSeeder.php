<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountiesSeeder::class,
            CitiesSeeder::class,
            CategoriesSeeder::class,
            UsersSeeder::class,
            ReviewsSeeder::class,
            AdvertisementsSeeder::class,
            JobsSeeder::class,
            TestUserSeeder::class,
            PenaltySeeder::class,
            AdminSeeder::class,
            ExtraDataSeeder::class,
            ]);
    }
}
