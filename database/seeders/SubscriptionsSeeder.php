<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        $subs = [
            ['name' => 'Alap', 'availability' => '2025-01-01 00:00:00', 'price' => 0, 'advertisment_number' => 2, 'advertisment_expiration' => 7],
            ['name' => 'Bronz', 'availability' => '2025-01-01 00:00:00', 'price' => 1500, 'advertisment_number' => 5, 'advertisment_expiration' => 14],
            ['name' => 'Ezüst', 'availability' => '2025-01-01 00:00:00', 'price' => 3500, 'advertisment_number' => 10, 'advertisment_expiration' => 30],
            ['name' => 'Arany', 'availability' => '2025-01-01 00:00:00', 'price' => 6000, 'advertisment_number' => 20, 'advertisment_expiration' => 60],
            ['name' => 'Platina', 'availability' => '2025-01-01 00:00:00', 'price' => 9000, 'advertisment_number' => 30, 'advertisment_expiration' => 90],
            ['name' => 'Vállalkozó', 'availability' => '2025-01-01 00:00:00', 'price' => 12000, 'advertisment_number' => 50, 'advertisment_expiration' => 120],
            ['name' => 'Családi', 'availability' => '2025-01-01 00:00:00', 'price' => 2000, 'advertisment_number' => 7, 'advertisment_expiration' => 21],
            ['name' => 'Próba', 'availability' => '2025-01-01 00:00:00', 'price' => 0, 'advertisment_number' => 1, 'advertisment_expiration' => 3],
            ['name' => 'Kiemelt', 'availability' => '2025-01-01 00:00:00', 'price' => 15000, 'advertisment_number' => 100, 'advertisment_expiration' => 180],
            ['name' => 'VIP', 'availability' => '2025-01-01 00:00:00', 'price' => 25000, 'advertisment_number' => 200, 'advertisment_expiration' => 365],
        ];

        foreach ($subs as $sub) {
            DB::table('subscription')->updateOrInsert(
                ['name' => $sub['name']],
                $sub
            );
        }
    }
}


