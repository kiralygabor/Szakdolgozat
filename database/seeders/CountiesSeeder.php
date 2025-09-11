<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $counties = [
            'Bács-Kiskun',
            'Baranya',
            'Békés',
            'Borsod-Abaúj-Zemplén',
            'Csongrád-Csanád',
            'Fejér',
            'Győr-Moson-Sopron',
            'Hajdú-Bihar',
            'Heves',
            'Jász-Nagykun-Szolnok',
            'Komárom-Esztergom',
            'Nógrád',
            'Pest',
            'Somogy',
            'Szabolcs-Szatmár-Bereg',
            'Tolna',
            'Vas',
            'Veszprém',
            'Zala',
        ];

        foreach ($counties as $county) {
            DB::table('counties')->insert([
                'name' => $county
            ]);
        }
    }
}
