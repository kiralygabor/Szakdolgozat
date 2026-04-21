<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->toArray();
        if (empty($userIds)) return;

        $reviewSamples = [
            ['stars' => 5, 'comment' => 'Nagyon meg voltam elégedve a munkájával. Precíz volt, pontosan érkezett, és a takarítás után minden ragyogott. Csak ajánlani tudom!'],
            ['stars' => 4, 'comment' => 'Alapvetően jól sikerült a szállítás, minden épségben megérkezett. Egy csillagot csak azért vonok le, mert 20 percet késett a megbeszélthez képest.'],
            ['stars' => 5, 'comment' => 'Profi szakember! A sövényvágás és a kertrendezés után végre újra öröm ránézni az udvarra. Minden hulladékot feltakarított maga után.'],
            ['stars' => 4, 'comment' => 'A gyermekfelügyelet rendben volt, a gyerekek szerették őt. Legközelebb is keresni fogjuk, ha segítség kell.'],
            ['stars' => 5, 'comment' => 'Nagyon kedves és segítőkész volt a kutyasétáltatás során. Látszik, hogy ért az állatokhoz, a kutyusom is rögtön megkedvelte.'],
            ['stars' => 3, 'comment' => 'A munka el lett végezve, de a kommunikáció kicsit nehézkes volt. Többször is át kellett beszélni ugyanazt a részletet.'],
            ['stars' => 5, 'comment' => 'Kiváló minőségű festés! Sehol egy folt vagy egy hiba, nagyon ügyelt a bútorok megóvására is. Nagyon hálás vagyok!'],
            ['stars' => 4, 'comment' => 'Gyorsan kihozta a csomagot, minden rendben ment. Kicsit nehéz volt parkolóhelyet találnia, de ez nem az ő hibája.'],
            ['stars' => 5, 'comment' => 'A gardrób összeszerelése sokkal gyorsabban ment, mint hittem. Minden rögzítés stabil, a fiókok simán járnak.'],
            ['stars' => 5, 'comment' => 'Remek oktató! A matek korrepetálás után a fiam sokkal magabiztosabb lett, és végre érti az összefüggéseket.'],
        ];

        foreach ($reviewSamples as $index => $sample) {
            // Assign varied reviewers and targets
            $reviewerId = $userIds[$index % count($userIds)];
            $targetId = $userIds[($index + 1) % count($userIds)];

            DB::table('reviews')->insert([
                'stars' => $sample['stars'],
                'comment' => $sample['comment'],
                'reviewer_id' => $reviewerId,
                'target_user_id' => $targetId,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}


