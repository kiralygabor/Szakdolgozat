<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvertisementsSeeder extends Seeder
{
    public function run(): void
    {
        // ensure required foreigns exist
        $userIds = DB::table('users')->pluck('id')->values();
        $categoryIds = DB::table('categories')->pluck('id')->values();
        $reviewIds = DB::table('reviews')->pluck('id')->values();

        if ($userIds->count() < 1 || $categoryIds->count() < 1 || $reviewIds->count() < 1) {
            return;
        }

        $ads = [];
        $samples = [
            ['title' => 'Lakás nagytakarítás', 'description' => 'Két szobás lakás teljes takarítása szombaton délelőtt.', 'price' => 18000, 'status' => 'closed', 'location' => 'Budapest'],
            ['title' => 'Tavaszi kertgondozás', 'description' => 'Sövényvágás, gyomlálás és fűnyírás egy családi házban.', 'price' => 25000, 'status' => 'closed', 'location' => 'Debrecen'],
            ['title' => 'Gyermekfelügyelet délutánra', 'description' => 'Két kisgyerekre vigyázás hétvégén.', 'price' => 12000, 'status' => 'closed', 'location' => 'Szeged'],
            ['title' => 'Kutyasétáltatás reggelente', 'description' => 'Két közepes méretű kutya sétáltatása naponta.', 'price' => 8000, 'status' => 'closed', 'location' => 'Pécs'],
            ['title' => 'Csomagszállítás a belvárosban', 'description' => 'Kisebb csomagok kézbesítése a belvárosban.', 'price' => 10000, 'status' => 'closed', 'location' => 'Győr'],
        ];

        for ($i = 0; $i < count($samples); $i++) {
            $employerIndex = $i % $userIds->count();
            $employeeIndex = ($i + 1) % $userIds->count();
            $categoryIndex = $i % $categoryIds->count();
            $reviewIndex = $i % $reviewIds->count();

            $sample = $samples[$i];
            $ads[] = [
                'categories_id' => $categoryIds[$categoryIndex],
                'reviews_id' => $reviewIds[$reviewIndex],
                'employer_id' => $userIds[$employerIndex],
                'employee_id' => $userIds[$employeeIndex],
                'location' => $sample['location'],
                'title' => $sample['title'],
                'description' => $sample['description'],
                'price' => $sample['price'],
                'created_at' => now()->subDays(3 - $i),
                'expiration_date' => now()->addDays(7 + $i),
                'status' => $sample['status'],
            ];
        }

        foreach ($ads as $ad) {
            DB::table('advertisments')->insert($ad);
        }
    }
}


