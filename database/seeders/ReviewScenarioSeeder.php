<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Advertisement;
use App\Models\Offer;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use App\Models\City;

class ReviewScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder sets up a scenario specifically for testing the review system.
     * It creates two users, a completed task, and an accepted offer between them.
     */
    public function run(): void
    {
        // 1. Create or Find Users
        // Reviewer (The one who will leave the review)
        $reviewer = User::firstOrCreate(
            ['email' => 'reviewer@example.com'],
            [
                'first_name' => 'Reviewer',
                'last_name' => 'Person',
                'password' => Hash::make('password'),
                'account_id' => 'REVIEWER001',
                'city_id' => City::first()->id ?? null,
                'phone_number' => '5551234567'
            ]
        );

        // Target (The one receiving the review - e.g., the Employer)
        $target = User::firstOrCreate(
            ['email' => 'target@example.com'],
            [
                'first_name' => 'Target',
                'last_name' => 'Person',
                'password' => Hash::make('password'),
                'account_id' => 'TARGET001',
                'city_id' => City::first()->id ?? null,
                'phone_number' => '5559876543'
            ]
        );

        // 2. Create a Completed Task where Target is Employer
        $category = Category::first() ?? Category::create(['name' => 'General']);
        $job = \App\Models\Job::first() ?? \App\Models\Job::create(['name' => 'General Job', 'categories_id' => $category->id]);

        $task = Advertisement::create([
            'employer_id' => $target->id,
            'jobs_id' => $job->id,
            'title' => 'Completed Task for Review Test',
            'description' => 'This is a test task that has been completed so the reviewer can leave a review.',
            'location' => 'Remote',
            'price' => 5000, // HUF or currency
            'expiration_date' => now()->addDays(5),
            'status' => 'completed', // Key status
            'created_at' => now()->subDays(5),
            'updated_at' => now(),
        ]);

        // 3. Create an Accepted Offer from Reviewer
        Offer::create([
            'advertisement_id' => $task->id,
            'user_id' => $reviewer->id,
            'price' => 5000,
            'message' => 'I did this cleanly.',
            'status' => 'accepted', // Key status
        ]);

        $this->command->info("Scenario Created!");
        $this->command->info("Login as Reviewer: reviewer@example.com / password");
        $this->command->info("Visit Target's Profile: /profile/{$target->id}");
    }
}
