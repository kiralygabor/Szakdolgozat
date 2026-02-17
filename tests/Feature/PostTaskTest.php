<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_post_task_and_it_is_saved_in_database()
    {
        Storage::fake('public');

        // create user (insert via DB to match custom users schema) and category
        $now = now();
        $account = 'ACC' . Str::upper(Str::random(8));
        $userId = DB::table('users')->insertGetId([
            'account_id' => $account,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'tester+' . Str::random(5) . '@example.com',
            'password' => Hash::make('password'),
            'created_at' => $now,
            'updated_at' => $now,
            'verified' => 1,
        ]);

        $user = User::find($userId);
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Cleaning',
            'image_url' => null,
            'description' => null,
        ]);
        $category = Category::find($categoryId);

        // Create a job for this category
        $jobId = DB::table('jobs')->insertGetId([
            'name' => 'House Cleaning',
            'categories_id' => $categoryId,
        ]);

        // act as authenticated user
        $this->actingAs($user);

        $payload = [
            'title' => 'Test Task',
            'description' => 'Clean my apartment',
            'price' => 150,
            'location' => 'Budapest',
            'task_type' => 'in-person',
            'categories_id' => $category->id,
            'jobs_id' => $jobId,
            'is_date_flexible' => 0,
        ];

        $response = $this->post(route('advertisements.store'), $payload);

        $response->assertRedirect(route('my-tasks'));

        $this->assertDatabaseHas('advertisements', [
            'title' => 'Test Task',
            'employer_id' => $user->id,
            'categories_id' => $category->id,
            'price' => 150,
        ]);
    }
}
