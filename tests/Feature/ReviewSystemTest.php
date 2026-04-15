<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

/**
 * Review System Tests
 *
 * Validates the peer review system: leaving reviews after task completion,
 * preventing self-reviews, preventing duplicate reviews, and ensuring
 * only users who have completed work together can review each other.
 */
class ReviewSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $employer;
    private User $worker;
    private User $stranger;
    private Advertisement $completedTask;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employer = User::factory()->create();
        $this->worker   = User::factory()->create();
        $this->stranger = User::factory()->create();

        $categoryId = DB::table('categories')->insertGetId([
            'name'        => 'Plumbing',
            'image_url'   => null,
            'description' => null,
        ]);

        $jobId = DB::table('jobs')->insertGetId([
            'name'          => 'Pipe Repair',
            'categories_id' => $categoryId,
        ]);

        $this->completedTask = Advertisement::create([
            'title'           => 'Fix kitchen sink',
            'description'     => 'Leaking pipe.',
            'price'           => 80,
            'location'        => 'Budapest',
            'task_type'       => 'in-person',
            'jobs_id'         => $jobId,
            'employer_id'     => $this->employer->id,
            'employee_id'     => $this->worker->id,
            'status'          => TaskStatus::Completed,
            'expiration_date' => now()->addDays(30),
        ]);
    }

    // ── LEAVING REVIEWS ──────────────────────────────────────

    #[Test]
    public function employer_can_review_worker_after_completion(): void
    {
        $this->actingAs($this->employer);

        $response = $this->post(route('public-profile.review', $this->worker->id), [
            'stars'   => 5,
            'comment' => 'Excellent work, very professional!',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'reviewer_id'    => $this->employer->id,
            'target_user_id' => $this->worker->id,
            'stars'          => 5,
        ]);
    }

    #[Test]
    public function worker_can_review_employer_after_completion(): void
    {
        $this->actingAs($this->worker);

        $response = $this->post(route('public-profile.review', $this->employer->id), [
            'stars'   => 4,
            'comment' => 'Clear instructions, easy to work with.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'reviewer_id'    => $this->worker->id,
            'target_user_id' => $this->employer->id,
            'stars'          => 4,
        ]);
    }

    // ── REVIEW GUARDS ────────────────────────────────────────

    #[Test]
    public function user_cannot_review_themselves(): void
    {
        $this->actingAs($this->employer);

        $response = $this->post(route('public-profile.review', $this->employer->id), [
            'stars'   => 5,
            'comment' => 'I am great',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('reviews', [
            'reviewer_id'    => $this->employer->id,
            'target_user_id' => $this->employer->id,
        ]);
    }

    #[Test]
    public function user_cannot_leave_duplicate_review(): void
    {
        Review::create([
            'reviewer_id'    => $this->employer->id,
            'target_user_id' => $this->worker->id,
            'stars'          => 5,
            'comment'        => 'First review',
        ]);

        $this->actingAs($this->employer);

        $response = $this->post(route('public-profile.review', $this->worker->id), [
            'stars'   => 3,
            'comment' => 'Second review attempt',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Review::where('reviewer_id', $this->employer->id)->count());
    }

    #[Test]
    public function stranger_cannot_review_without_completed_task(): void
    {
        $this->actingAs($this->stranger);

        $response = $this->post(route('public-profile.review', $this->worker->id), [
            'stars'   => 1,
            'comment' => 'Bad',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('reviews', [
            'reviewer_id' => $this->stranger->id,
        ]);
    }

    // ── USER RATING CALCULATION ──────────────────────────────

    #[Test]
    public function user_rating_is_calculated_from_reviews(): void
    {
        Review::create([
            'reviewer_id'    => $this->employer->id,
            'target_user_id' => $this->worker->id,
            'stars'          => 5,
            'comment'        => 'Perfect',
        ]);

        Review::create([
            'reviewer_id'    => $this->stranger->id,
            'target_user_id' => $this->worker->id,
            'stars'          => 3,
            'comment'        => 'Okay',
        ]);

        $this->assertEquals(4.0, $this->worker->fresh()->rating);
    }
}
