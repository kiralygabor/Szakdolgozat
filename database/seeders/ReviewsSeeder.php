<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            ['stars' => 5, 'comment' => 'Nagyon elégedett voltam, kiváló munka!'],
            ['stars' => 4, 'comment' => 'Jól dolgozott, de kicsit késett a megbeszélt időpontról.'],
            ['stars' => 5, 'comment' => 'Gyors, precíz és udvarias, csak ajánlani tudom.'],
            ['stars' => 3, 'comment' => 'Elfogadható munkát végzett, de lehetett volna alaposabb.'],
            ['stars' => 5, 'comment' => 'Tökéletes eredmény, minden elvárásomat túlteljesítette.'],
            ['stars' => 4, 'comment' => 'Megbízható, korrekt kommunikációval.'],
            ['stars' => 2, 'comment' => 'Nem azt kaptam, amit vártam.'],
            ['stars' => 5, 'comment' => 'Profi munka, határidő előtt kész.'],
            ['stars' => 4, 'comment' => 'Összességében rendben volt.'],
            ['stars' => 5, 'comment' => 'Kiemelkedő minőség és hozzáállás.'],
        ];

        foreach ($reviews as $review) {
            DB::table('reviews')->insert($review);
        }
    }
}


