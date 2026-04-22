<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

/**
 * Report System Tests
 *
 * Validates the task reporting functionality: authenticated users reporting
 * tasks that violate community guidelines. Ensures proper authorization
 * and data persistence.
 */
class ReportSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $reporter;
    private User $offender;
    private Advertisement $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reporter = User::factory()->create();
        $this->offender = User::factory()->create();

        $categoryId = DB::table('categories')->insertGetId([
            'name'        => 'General',
            'image_url'   => null,
            'description' => null,
        ]);

        $jobId = DB::table('jobs')->insertGetId([
            'name'          => 'Other',
            'categories_id' => $categoryId,
        ]);

        $this->task = Advertisement::create([
            'title'           => 'Suspicious task',
            'description'     => 'Might violate guidelines.',
            'price'           => 50,
            'location'        => 'Budapest',
            'task_type'       => 'in-person',
            'jobs_id'         => $jobId,
            'employer_id'     => $this->offender->id,
            'status'          => TaskStatus::Open,
            'expiration_date' => now()->addDays(30),
        ]);
    }

    // ── REPORT SUBMISSION ────────────────────────────────────

    #[Test]
    public function authenticated_user_can_report_a_task(): void
    {
        $this->actingAs($this->reporter);

        $response = $this->post(route('reports.store'), [
            'advertisement_id'    => $this->task->id,
            'reported_account_id' => $this->offender->id,
            'description'         => 'This task contains inappropriate content.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('advertisement_reports', [
            'advertisement_id'    => $this->task->id,
            'reporter_account_id' => $this->reporter->account_id,
            'reported_account_id' => $this->offender->account_id,
        ]);
    }

    #[Test]
    public function guest_cannot_submit_a_report(): void
    {
        $response = $this->post(route('reports.store'), [
            'advertisement_id'    => $this->task->id,
            'reported_account_id' => $this->offender->id,
            'description'         => 'Spam',
        ]);

        $response->assertRedirect(route('login'));
    }
}
