<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        $subs = [
            ['name' => 'Alap', 'availability' => 7, 'price' => 0, 'advertisment_number' => 2, 'advertisment_expiration' => '2025-01-01 00:00:00'],
            ['name' => 'Bronz', 'availability' => 14, 'price' => 1500, 'advertisment_number' => 5, 'advertisment_expiration' => '2025-01-01 00:00:00'],
            ['name' => 'Ezüst', 'availability' => 30, 'price' => 3500, 'advertisment_number' => 10, 'advertisment_expiration' => '2025-01-01 00:00:00'],
            ['name' => 'Arany', 'availability' => 60, 'price' => 6000, 'advertisment_number' => 20, 'advertisment_expiration' => '2025-01-01 00:00:00'],
        ];

        foreach ($subs as $sub) {
            DB::table('subscription')->updateOrInsert(
                ['name' => $sub['name']],
                $sub
            );
        }
    }
}


