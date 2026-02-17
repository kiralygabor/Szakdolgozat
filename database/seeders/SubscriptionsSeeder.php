<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        $subs = [
            ['name' => 'Alap', 'availability' => 7, 'price' => 0, 'advertisement_number' => 2, 'advertisement_expiration' => '2025-01-01 00:00:00'],
            ['name' => 'Bronz', 'availability' => 14, 'price' => 1500, 'advertisement_number' => 5, 'advertisement_expiration' => '2025-01-01 00:00:00'],
            ['name' => 'Ezüst', 'availability' => 30, 'price' => 3500, 'advertisement_number' => 10, 'advertisement_expiration' => '2025-01-01 00:00:00'],
            ['name' => 'Arany', 'availability' => 60, 'price' => 6000, 'advertisement_number' => 20, 'advertisement_expiration' => '2025-01-01 00:00:00'],
        ];

        foreach ($subs as $sub) {
            DB::table('subscription')->updateOrInsert(
                ['name' => $sub['name']],
                $sub
            );
        }
    }
}


