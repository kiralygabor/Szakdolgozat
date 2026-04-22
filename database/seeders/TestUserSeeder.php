<?php
 
namespace Database\Seeders;
 
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
 
class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Teszt',
                'last_name' => 'Elek',
                'password' => Hash::make('password'),
                'account_id' => 'AC1007',
                'verified' => true,
                'phone_number' => '+36111111111',
            ]
        );

        User::updateOrCreate(
            ['email' => 'test2@example.com'],
            [
                'first_name' => 'Próba',
                'last_name' => 'Péter',
                'password' => Hash::make('password2'),
                'account_id' => 'AC1008',
                'verified' => true,
                'phone_number' => '+36222222222',
            ]
        );
    }
}