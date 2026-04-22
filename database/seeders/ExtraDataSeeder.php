<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Advertisement;
use App\Models\Offer;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\AdvertisementView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ExtraDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();
        $advertisements = Advertisement::all();

        if ($users->count() < 2 || $advertisements->isEmpty()) {
            $this->command->warn('Not enough users or advertisements to seed extra data.');
            return;
        }

        // 1. Create Offers
        $offerMessages = [
            'Szia! Nagyon szívesen segítek a takarításban. Tapasztalt vagyok, és minden szükséges eszközöm megvan.',
            'Üdv! El tudom vállalni a szállítást a hétvégén. Van egy tágas furgonom, minden épségben megérkezik.',
            'Szia, érdekelne a kertmunka. Péntek délután vagy szombat reggel lenne neked alkalmasabb?',
            'Üdvözlöm! Több éves tapasztalatom van ilyen szekrények összeszerelésében, szívesen megcsinálom.',
            'Szia! Épp a közelben lakom, így rugalmasan tudok menni segíteni a kutyasétáltatásban.',
            'Üdv! Referenciákkal rendelkezem, megbízható és precíz munkát végzek. Mikor kezdhetünk?',
        ];

        foreach ($advertisements as $advertisement) {
            $potentialBidders = $users->where('id', '!=', $advertisement->employer_id);
            if ($potentialBidders->isEmpty()) continue;

            $numOffers = rand(1, 3);
            $bidders = $potentialBidders->random(min($numOffers, $potentialBidders->count()));

            foreach ($bidders as $bidder) {
                Offer::create([
                    'advertisement_id' => $advertisement->id,
                    'user_id' => $bidder->id,
                    'price' => min(5000, $advertisement->price * (rand(90, 110) / 100)),
                    'message' => $faker->randomElement($offerMessages),
                    'status' => $faker->randomElement(['pending', 'accepted', 'rejected']),
                ]);
            }
        }
        $this->command->info('Offers seeded!');

        // 2. Create Conversations and Messages
        $chatMessages = [
            'Szia! Köszönöm az ajánlatot, mikor tudnál jönni?',
            'Üdv! Holnap délután 3 óra megfelel neked?',
            'Szuper, ott leszek! Pontos címet tudnál küldeni?',
            'Persze, elküldtem üzenetben. Várlak holnap.',
            'Szia, megkaptam a címet. Még egy kérdés: eszközöket kell hoznom?',
            'Nem szükséges, minden van nálam a takarításhoz.',
            'Rendben, akkor holnap találkozunk!',
            'Szia! Ma sajnos kicsit késni fogok a forgalom miatt, kb. 15 perc.',
            'Semmi gond, várlak. Köszönöm, hogy szóltál!',
            'Megérkeztem, itt vagyok a ház előtt.',
        ];

        for ($i = 0; $i < 10; $i++) {
            $u1 = $users->random();
            $u2 = $users->where('id', '!=', $u1->id)->random();

            $userIds = [$u1->id, $u2->id];
            sort($userIds);

            $conversation = Conversation::firstOrCreate([
                'user_one_id' => $userIds[0],
                'user_two_id' => $userIds[1],
            ]);

            $numMsgs = rand(4, 10);
            for ($j = 0; $j < $numMsgs; $j++) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $faker->randomElement($userIds),
                    'body' => $faker->randomElement($chatMessages),
                    'is_read' => $faker->boolean(80),
                    'created_at' => now()->subMinutes(rand(1, 10000)),
                ]);
            }
        }
        $this->command->info('Conversations and Messages seeded!');

        // 3. Advertisement Views
        foreach ($advertisements as $advertisement) {
            $viewers = $users->random(min(5, $users->count()));
            foreach ($viewers as $viewer) {
                AdvertisementView::firstOrCreate([
                    'advertisement_id' => $advertisement->id,
                    'user_id' => $viewer->id,
                ]);
            }
        }
        $this->command->info('Advertisement Views seeded!');

        // 4. Reports
        $adReportMessages = [
            'Spam: Ez a hirdetés többször is fel lett töltve különböző néven.',
            'Hibás kategória: A szekrény szerelés nem az Autó kategóriába való.',
            'Gyanús hirdetés: Túl alacsony ár, valószínűleg csalás/scam.',
            'Tiltott tartalom: A hirdetés olyan szolgáltatást kínál, ami nem megengedett.',
            'Hiányos adatok: Nincs megadva pontos helyszín vagy leírás.',
        ];

        $userReportMessages = [
            'Sértő stílus: Alpári és fenyegető hangvételű üzenetek az üzletkötés során.',
            'Kamu profil: A képek és az adatok alapján nem valós személynek tűnik.',
            'Zaklatás: Többszöri kéretlen megkeresés a nemleges válasz után is.',
            'Csalási kísérlet: Gyanús külső linkekre akart átirányítani fizetés miatt.',
        ];

        $reporters = $users->random(min(5, $users->count()));
        foreach ($reporters as $index => $reporter) {
            // Unique-ish reports using index
            $adReason = $adReportMessages[$index % count($adReportMessages)];
            $userReason = $userReportMessages[$index % count($userReportMessages)];

            // Report a random advertisement
            $targetAd = $advertisements->random();
            DB::table('advertisement_reports')->insert([
                'advertisement_id' => $targetAd->id,
                'description' => $adReason,
                'reporter_account_id' => $reporter->account_id,
                'reported_account_id' => $targetAd->employer->account_id ?? $users->random()->account_id,
                'status' => $faker->randomElement(['open', 'closed']),
                'created_at' => now()->subDays(rand(1, 5)),
            ]);

            // Report a random user
            $targetUser = $users->where('id', '!=', $reporter->id)->random();
            DB::table('user_reports')->insert([
                'description' => $userReason,
                'reporter_account_id' => $reporter->account_id,
                'reported_account_id' => $targetUser->account_id,
                'status' => $faker->randomElement(['open', 'closed']),
                'created_at' => now()->subDays(rand(1, 5)),
            ]);
        }
        $this->command->info('Reports seeded!');

        // 5. Blacklist
        $penalties = DB::table('penalties')->pluck('id');
        if ($penalties->isNotEmpty()) {
            for ($i = 0; $i < 3; $i++) {
                $targetUser = $users->random();
                DB::table('blacklist')->insert([
                    'user_id' => $targetUser->id,
                    'penalty_id' => $penalties->random(),
                    'expiration_date' => now()->addDays(rand(1, 30)),
                    'comment' => 'Többszöri szabályszegés miatt kitiltva.',
                ]);
            }
            $this->command->info('Blacklist seeded!');
        }

        // 6. Notifications
        $notifData = [
            ['message' => 'Új ajánlat érkezett a hirdetésedre!', 'type' => 'App\Notifications\TaskOfferReceived'],
            ['message' => 'Elfogadták az ajánlatodat! Gratulálunk!', 'type' => 'App\Notifications\OfferAccepted'],
            ['message' => 'Új üzeneted érkezett a beszélgetésben.', 'type' => 'App\Notifications\NewMessage'],
        ];

        foreach ($users->random(5) as $user) {
            $chosen = $faker->randomElement($notifData);
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => $chosen['type'],
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'data' => json_encode(['message' => $chosen['message']]),
                'read_at' => $faker->boolean(50) ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->command->info('Notifications seeded!');
        $this->command->info('Notifications seeded!');
    }
}
