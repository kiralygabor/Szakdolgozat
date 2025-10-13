<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Takarítás'],
            ['name' => 'Kertészkedés'],
            ['name' => 'Gyermekfelügyelet'],
            ['name' => 'Kutyasétáltatás'],
            ['name' => 'Futárszolgálat'],
            ['name' => 'Kisebb javítások'],
            ['name' => 'Költöztetés'],
            ['name' => 'Idősgondozás'],
            ['name' => 'Fordítás'],
            ['name' => 'Adminisztrációs segítség'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                $category
            );
        }
    }
}


