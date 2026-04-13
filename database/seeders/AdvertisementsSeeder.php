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
        $jobIds = DB::table('jobs')->pluck('id')->values();
        $reviewIds = DB::table('reviews')->pluck('id')->values();

        if ($userIds->count() < 1 || $jobIds->count() < 1 || $reviewIds->count() < 1) {
            return;
        }

        $ads = [];
        $samples = [
            ['title' => 'Lakás nagytakarítás', 'description' => 'Két szobás lakás teljes takarítása szombaton délelőtt.', 'price' => 3000, 'status' => 'open', 'location' => 'Budapest'],
            ['title' => 'Tavaszi kertgondozás', 'description' => 'Sövényvágás, gyomlálás és fűnyírás egy családi házban.', 'price' => 2000, 'status' => 'open', 'location' => 'Debrecen'],
            ['title' => 'Gyermekfelügyelet délutánra', 'description' => 'Két kisgyerekre vigyázás hétvégén.', 'price' => 4000, 'status' => 'open', 'location' => 'Szeged'],
            ['title' => 'Kutyasétáltatás reggelente', 'description' => 'Két közepes méretű kutya sétáltatása naponta.', 'price' => 1000, 'status' => 'open', 'location' => 'Pécs'],
            ['title' => 'Csomagszállítás a belvárosban', 'description' => 'Kisebb csomagok kézbesítése a belvárosban.', 'price' => 1000, 'status' => 'open', 'location' => 'Győr'],
        ];

        for ($i = 0; $i < count($samples); $i++) {
            $employerIndex = $i % $userIds->count();
            $employeeIndex = ($i + 1) % $userIds->count();
            $jobIndex = $i % $jobIds->count();
            $reviewIndex = $i % $reviewIds->count();

            $sample = $samples[$i];
            $ads[] = [
                'jobs_id' => $jobIds[$jobIndex],
                'reviews_id' => $reviewIds[$reviewIndex],
                'employer_id' => $userIds[$employerIndex],
                'employee_id' => null,
                'location' => $sample['location'],
                'title' => $sample['title'],
                'description' => $sample['description'],
                'price' => $sample['price'],
                'created_at' => now()->subDays(3 - $i),
                'expiration_date' => now()->addDays(7 + $i),
                'status' => $sample['status'],
            ];
        }

        // Extra advertisement specifically owned by the test user
        $testUserId = DB::table('users')->where('email', 'test2@example.hu')->value('id');
        $fallbackEmployerId = $userIds[0] ?? null;
        $employeeId = $userIds[1] ?? $fallbackEmployerId;
        $jobId = $jobIds[0] ?? null;
        $reviewId = $reviewIds[0] ?? null;

        if ($testUserId && $jobId && $reviewId) {
            $ads[] = [
                'jobs_id' => $jobId,
                'reviews_id' => $reviewId,
                'employer_id' => $testUserId,
                'employee_id' => $employeeId,
                'location' => 'Budapest',
                'title' => 'Teszt feladat a próba felhasználónak',
                'description' => 'Ez egy teszt hirdetés, amelyet a test2@example.hu felhasználó hozott létre.',
                'price' => 20000,
                'created_at' => now()->subDay(),
                'expiration_date' => now()->addDays(10),
                'status' => 'open',
            ];
        }

        foreach ($ads as $ad) {
            DB::table('advertisements')->insert($ad);
        }
    }
}


