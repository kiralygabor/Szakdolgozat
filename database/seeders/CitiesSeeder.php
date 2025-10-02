<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $file = database_path('seeders/data/cities.csv');

        if (($handle = fopen($file, 'r')) !== false) {
            fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                $postcode = $row[0];
                $name = $row[1];
                $countyName = $row[2];

                $countyId = DB::table('counties')->where('name', $countyName)->value('id');

                DB::table('cities')->insert([
                    'postcode' => $postcode,
                    'name' => $name,
                    'county_id' => $countyId,
                ]);
            }
            fclose($handle);
        }
    }
}
