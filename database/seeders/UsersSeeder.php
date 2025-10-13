<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $cityId = DB::table('cities')->value('id');
        $subId = DB::table('subscription')->value('id');

        $users = [
            [
                'account_id' => 'AC1001',
                'first_name' => 'Kovács',
                'last_name' => 'Péter',
                'birthdate' => '1990-04-12',
                'phone_number' => '+36301234567',
                'email' => 'peter.kovacs@example.hu'
            ],
            [
                'account_id' => 'AC1002',
                'first_name' => 'Nagy',
                'last_name' => 'Mária',
                'birthdate' => '1987-11-23',
                'phone_number' => '+36304561234',
                'email' => 'maria.nagy@example.hu'
            ],
            [
                'account_id' => 'AC1003',
                'first_name' => 'Szabó',
                'last_name' => 'Gergely',
                'birthdate' => '1995-06-30',
                'phone_number' => '+36205553322',
                'email' => 'gergely.szabo@example.hu'
            ],
            [
                'account_id' => 'AC1004',
                'first_name' => 'Tóth',
                'last_name' => 'Eszter',
                'birthdate' => '1992-02-19',
                'phone_number' => '+36701234566',
                'email' => 'eszter.toth@example.hu'
            ],
            [
                'account_id' => 'AC1005',
                'first_name' => 'Kiss',
                'last_name' => 'Tamás',
                'birthdate' => '1991-12-10',
                'phone_number' => '+36204443322',
                'email' => 'tamas.kiss@example.hu'
            ],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['email' => $u['email']],
                [
                    'account_id' => $u['account_id'],
                    'first_name' => $u['first_name'],
                    'last_name' => $u['last_name'],
                    'birthdate' => $u['birthdate'],
                    'phone_number' => $u['phone_number'],
                    'password' => Hash::make('password'),
                    'email' => $u['email'],
                    'city_id' => $cityId,
                    'subscription_id' => $subId,
                    'verified' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}


