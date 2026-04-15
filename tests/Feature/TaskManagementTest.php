<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\User;
use App\Http\Middleware\EnsureProfileComplete;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

/**
 * Task Management Tests
 *
 * Validates the full task lifecycle: creation, browsing, viewing details,
 * completing, and deleting tasks. Ensures authorization guards and
 * validation rules are enforced correctly.
 */
class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $employer;
    private User $worker;
    private int $jobId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([EnsureProfileComplete::class]);
        Storage::fake('public');

        $this->employer = User::factory()->create();
        $this->worker   = User::factory()->create();

        $categoryId = DB::table('categories')->insertGetId([
            'name'        => 'Cleaning',
            'image_url'   => null,
            'description' => null,
        ]);

        $this->jobId = DB::table('jobs')->insertGetId([
            'name'          => 'House Cleaning',
            'categories_id' => $categoryId,
        ]);
    }

    // ── TASK CREATION ────────────────────────────────────────

    #[Test]
    public function guest_cannot_access_post_task_page(): void
    {
        $response = $this->get(route('post-task'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_can_view_post_task_form(): void
    {
        $this->actingAs($this->employer);

        $response = $this->get(route('post-task'));

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_create_a_task(): void
    {
        $this->actingAs($this->employer);

        $response = $this->post(route('tasks.store'), $this->validTaskData([
            'title' => 'Clean my apartment',
        ]));

        $response->assertRedirect(route('my-tasks', ['view' => 'posted']));
        $this->assertDatabaseHas('advertisements', [
            'title'       => 'Clean my apartment',
            'employer_id' => $this->employer->id,
            'status'      => TaskStatus::Open->value,
        ]);
    }

    #[Test]
    public function task_creation_fails_without_required_fields(): void
    {
        $this->actingAs($this->employer);

        $response = $this->post(route('tasks.store'), []);

        $response->assertSessionHasErrors(['title', 'description', 'price', 'task_type', 'jobs_id']);
    }

    #[Test]
    public function task_price_must_be_between_5_and_5000(): void
    {
        $this->actingAs($this->employer);

        $response = $this->post(route('tasks.store'), $this->validTaskData([
            'price' => 3,
        ]));

        $response->assertSessionHasErrors('price');

        $response = $this->post(route('tasks.store'), $this->validTaskData([
            'price' => 6000,
        ]));

        $response->assertSessionHasErrors('price');
    }

    // ── TASK BROWSING ────────────────────────────────────────

    #[Test]
    public function tasks_listing_page_is_publicly_accessible(): void
    {
        $response = $this->get(route('tasks'));

        $response->assertStatus(200);
    }

    #[Test]
    public function task_details_page_shows_task_information(): void
    {
        $task = Advertisement::create(array_merge($this->validTaskData(), [
            'employer_id'     => $this->employer->id,
            'status'          => TaskStatus::Open,
            'expiration_date' => now()->addDays(30),
        ]));

        $response = $this->get(route('tasks.show', $task));

        $response->assertStatus(200);
        $response->assertSee($task->title);
    }

    // ── TASK COMPLETION ──────────────────────────────────────

    #[Test]
    public function employer_can_complete_an_assigned_task(): void
    {
        $task = Advertisement::create(array_merge($this->validTaskData(), [
            'employer_id'     => $this->employer->id,
            'employee_id'     => $this->worker->id,
            'status'          => TaskStatus::Assigned,
            'expiration_date' => now()->addDays(30),
        ]));

        $this->actingAs($this->employer);

        $response = $this->post(route('tasks.complete', $task));

        $response->assertSessionHas('success');
        $this->assertEquals(TaskStatus::Completed, $task->fresh()->status);
    }

    #[Test]
    public function non_owner_cannot_complete_a_task(): void
    {
        $task = Advertisement::create(array_merge($this->validTaskData(), [
            'employer_id'     => $this->employer->id,
            'employee_id'     => $this->worker->id,
            'status'          => TaskStatus::Assigned,
            'expiration_date' => now()->addDays(30),
        ]));

        $this->actingAs($this->worker);

        $response = $this->post(route('tasks.complete', $task));

        $response->assertForbidden();
    }

    // ── TASK DELETION ────────────────────────────────────────

    #[Test]
    public function owner_can_delete_their_own_task(): void
    {
        $task = Advertisement::create(array_merge($this->validTaskData(), [
            'employer_id'     => $this->employer->id,
            'status'          => TaskStatus::Open,
            'expiration_date' => now()->addDays(30),
        ]));

        $this->actingAs($this->employer);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertSessionHas('success');
        $this->assertSoftDeleted('advertisements', ['id' => $task->id]);
    }

    #[Test]
    public function non_owner_cannot_delete_a_task(): void
    {
        $task = Advertisement::create(array_merge($this->validTaskData(), [
            'employer_id'     => $this->employer->id,
            'status'          => TaskStatus::Open,
            'expiration_date' => now()->addDays(30),
        ]));

        $this->actingAs($this->worker);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertForbidden();
    }

    // ── MY TASKS DASHBOARD ───────────────────────────────────

    #[Test]
    public function guest_cannot_access_my_tasks(): void
    {
        $response = $this->get(route('my-tasks'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_can_view_my_tasks(): void
    {
        $this->actingAs($this->employer);

        $response = $this->get(route('my-tasks'));

        $response->assertStatus(200);
    }

    // ── HELPERS ──────────────────────────────────────────────

    private function validTaskData(array $overrides = []): array
    {
        return array_merge([
            'title'           => 'Test Task',
            'description'     => 'A detailed description of the task.',
            'price'           => 100,
            'location'        => 'Budapest',
            'task_type'       => 'in-person',
            'jobs_id'         => $this->jobId,
            'is_date_flexible' => 0,
        ], $overrides);
    }
}
