<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Http\Middleware\EnsureProfileComplete;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class PostTaskTest extends TestCase
{
    use RefreshDatabase;

    private int $jobId;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->withoutMiddleware([EnsureProfileComplete::class]);
        Storage::fake('public');

        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Cleaning',
            'image_url' => null,
            'description' => null,
        ]);

        $this->jobId = DB::table('jobs')->insertGetId([
            'name' => 'House Cleaning',
            'categories_id' => $categoryId,
        ]);
    }

    #[Test]
    public function authenticated_user_can_submit_valid_task()
    {
        $this->actingAs(User::factory()->create());
        
        $response = $this->post(route('tasks.store'), $this->validTaskData());

        $response->assertRedirect(route('my-tasks', ['view' => 'posted']));
    }

    #[Test]
    public function submitting_task_persists_to_database()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('tasks.store'), $this->validTaskData(['title' => 'House Cleanup']));

        $this->assertDatabaseHas('advertisements', [
            'title' => 'House Cleanup',
            'employer_id' => $user->id
        ]);
    }

    private function validTaskData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Test Task',
            'description' => 'Requirement details',
            'price' => 100,
            'location' => 'Budapest',
            'task_type' => 'in-person',
            'jobs_id' => $this->jobId,
            'is_date_flexible' => 0,
        ], $overrides);
    }
}
