<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        if (DB::table('admins')->where('username', 'admin')->doesntExist()) {
            DB::table('admins')->insert([
                'username' => 'admin',
                'password' => Hash::make('123'),
                'profile_picture_path' => 'assets/img/default.jpg',
            ]);
            
            // Helpful console output
            $this->command->info('Admin user created successfully in the admins table!');
            $this->command->info('Username: admin');
            $this->command->info('Password: 123');
        } else {
            $this->command->info('Admin user already exists in the admins table.');
        }
    }
}
