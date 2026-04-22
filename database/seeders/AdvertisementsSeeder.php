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

        $samples = [
            [
                'title' => 'Irodai laptop kiválasztása és beállítása', 
                'description' => 'Segítséget keresek egy új munkára szánt laptop kiválasztásához és a szükséges szoftverek (Office, vírusirtó) telepítéséhez. Távolról is megoldható.', 
                'price' => 40, 'status' => 'open', 'location' => 'Budapest', 'job_index' => 8 // Lessons & Education / IT
            ],
            [
                'title' => 'Régi komód csiszolása és átfestése', 
                'description' => 'Egy közepes méretű fa komódot szeretnék felújítani. Csiszolás és két réteg krémszínű festés a feladat. Az anyagokat megveszem előre.', 
                'price' => 110, 'status' => 'open', 'location' => 'Debrecen', 'job_index' => 0 // Home Services
            ],
            [
                'title' => 'Lábadozó kutyus felügyelete hétköznap', 
                'description' => 'Műtét utáni kutyus mellé keresek felügyelőt napi 4-5 órára, amíg dolgozom. Csak nyugodt jelenlétre és néha víz itatásra van szükség.', 
                'price' => 35, 'status' => 'open', 'location' => 'Szeged', 'job_index' => 9 // Pet Services
            ],
            [
                'title' => 'Ereszcsatorna tisztítás családi házon', 
                'description' => 'Egyszintes családi ház ereszcsatornájából kellene kitisztítani a leveleket és a hordalékot. Létra van a helyszínen.', 
                'price' => 60, 'status' => 'open', 'location' => 'Győr', 'job_index' => 0 // Home Services
            ],
            [
                'title' => 'iPhone 13 kijelző csere', 
                'description' => 'Betört a telefonom kijelzője, keresek valakit, aki hozott vagy beszerzett alkatrésszel szakszerűen kicserélné még a héten.', 
                'price' => 90, 'status' => 'open', 'location' => 'Miskolc', 'job_index' => 4 // Automotive / Tech
            ],
            [
                'title' => 'Split klíma szezonális tisztítása', 
                'description' => 'Két beltéri egység és egy kültéri egység vegyszeres tisztításához és fertőtlenítéséhez keresek szakembert a nyári szezon előtt.', 
                'price' => 75, 'status' => 'open', 'location' => 'Budapest', 'job_index' => 0 // Home Services
            ],
            [
                'title' => 'WordPress weboldal sebesség optimalizálás', 
                'description' => 'A meglévő blogom nagyon lassan tölt be. Keresek egy fejlesztőt, aki rendbe teszi a plugineket és optimalizálja a képeket.', 
                'price' => 200, 'status' => 'open', 'location' => 'Székesfehérvár', 'job_index' => 12 // Miscellaneous / IT
            ],
            [
                'title' => 'Kezdő zongora oktatás felnőttnek', 
                'description' => 'Teljesen kezdő szinten szeretnék megtanulni zongorázni. Heti egy alkalommal keresek tanárt, aki házhoz jön vagy Skype-on tanít.', 
                'price' => 25, 'status' => 'open', 'location' => 'Veszprém', 'job_index' => 8 // Lessons & Education
            ],
            [
                'title' => 'Fűnyírás és bozótirtás elhanyagolt telken', 
                'description' => 'Körülbelül 800 nm-es, régóta nem gondozott telken kellene lenyírni a magas füvet és kiirtani a vadrózsákat. Erős fűkasza szükséges.', 
                'price' => 180, 'status' => 'open', 'location' => 'Szentendre', 'job_index' => 10 // Gardening
            ],
            [
                'title' => 'Beépített sütő beszerelése és bekötése', 
                'description' => 'Új konyhabútorba kellene behelyezni egy elektromos sütőt és bekötni a hálózatba. Villanyszerelői igazolás előny.', 
                'price' => 55, 'status' => 'open', 'location' => 'Budapest', 'job_index' => 5 // Trades & Construction
            ],
        ];

        for ($i = 0; $i < count($samples); $i++) {
            $employerId = $userIds[$i % $userIds->count()];
            $jobId = $jobIds[$samples[$i]['job_index'] % $jobIds->count()];
            $reviewId = $reviewIds[$i % $reviewIds->count()];

            DB::table('advertisements')->insert([
                'jobs_id' => $jobId,
                'reviews_id' => $reviewId,
                'employer_id' => $employerId,
                'employee_id' => null,
                'location' => $samples[$i]['location'],
                'title' => $samples[$i]['title'],
                'description' => $samples[$i]['description'],
                'price' => $samples[$i]['price'],
                'created_at' => now()->subDays(rand(1, 10)),
                'expiration_date' => now()->addDays(rand(5, 15)),
                'status' => 'open',
                'updated_at' => now(),
            ]);
        }

        // Add 5 COMPLETED advertisements with employees
        for ($i = 0; $i < 5; $i++) {
            $eIndex = ($i + 1) % $userIds->count();
            $employerId = $userIds[$i % $userIds->count()];
            $employeeId = $userIds[$eIndex];
            
            // Ensure employer and employee are different
            if ($employerId === $employeeId) {
                $employeeId = $userIds[($eIndex + 1) % $userIds->count()];
            }

            $jobId = $jobIds[rand(0, $jobIds->count() - 1)];
            $reviewId = $reviewIds[rand(0, $reviewIds->count() - 1)];

            DB::table('advertisements')->insert([
                'jobs_id' => $jobId,
                'reviews_id' => $reviewId,
                'employer_id' => $employerId,
                'employee_id' => $employeeId,
                'location' => 'Budapest',
                'title' => 'Befejezett feladat #' . ($i + 1),
                'description' => 'Ez egy sikeresen elvégzett és lezárt feladat a múltból.',
                'price' => rand(20, 100),
                'created_at' => now()->subMonths(1),
                'expiration_date' => now()->subDays(20),
                'status' => 'completed',
                'updated_at' => now()->subDays(20),
            ]);
        }

        // Add one specific task for the main test user
        $testUserId = DB::table('users')->where('email', 'test2@example.hu')->value('id');
        if ($testUserId) {
             DB::table('advertisements')->insert([
                'jobs_id' => $jobIds[0],
                'reviews_id' => $reviewIds[0],
                'employer_id' => $testUserId,
                'employee_id' => null,
                'location' => 'Budapest',
                'title' => 'Sürgős csőtörés elhárítása',
                'description' => 'A konyhai mosogató alatt szivárog a víz, azonnali segítségre lenne szükségem egy vízvezetékszerelőtől.',
                'price' => 300,
                'created_at' => now(),
                'expiration_date' => now()->addDays(7),
                'status' => 'open',
                'updated_at' => now(),
            ]);
        }
    }
}
