<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('penalties')->insert([
            ['name' => 'Némítás'],
            ['name' => 'Korlátozás'],
            ['name' => 'Tiltás'],
        ]);
    }
}
