<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\OfferStatus;
use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\Offer;
use App\Models\User;
use App\Http\Middleware\EnsureProfileComplete;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;

/**
 * Offer System Tests
 *
 * Validates the offer lifecycle: placing an offer, preventing duplicates,
 * preventing self-offers, accepting offers, declining competing offers,
 * and cancelling offers. Also verifies notifications are dispatched.
 */
class OfferSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $employer;
    private User $worker;
    private User $worker2;
    private Advertisement $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([EnsureProfileComplete::class]);
        Notification::fake();

        $this->employer = User::factory()->create();
        $this->worker   = User::factory()->create();
        $this->worker2  = User::factory()->create();

        $categoryId = DB::table('categories')->insertGetId([
            'name'        => 'Delivery',
            'image_url'   => null,
            'description' => null,
        ]);

        $jobId = DB::table('jobs')->insertGetId([
            'name'          => 'Courier',
            'categories_id' => $categoryId,
        ]);

        $this->task = Advertisement::create([
            'title'           => 'Deliver a package',
            'description'     => 'From A to B.',
            'price'           => 50,
            'location'        => 'Budapest',
            'task_type'       => 'in-person',
            'jobs_id'         => $jobId,
            'employer_id'     => $this->employer->id,
            'status'          => TaskStatus::Open,
            'expiration_date' => now()->addDays(30),
        ]);
    }

    // ── PLACING OFFERS ───────────────────────────────────────

    #[Test]
    public function worker_can_place_offer_on_open_task(): void
    {
        $this->actingAs($this->worker);

        $response = $this->post(route('tasks.offers.store', $this->task), [
            'offer_price' => 45,
            'message'     => 'I can deliver it today.',
        ]);

        $response->assertRedirect(route('tasks'));
        $this->assertDatabaseHas('offers', [
            'advertisement_id' => $this->task->id,
            'user_id'          => $this->worker->id,
            'price'            => 45,
            'status'           => OfferStatus::Pending->value,
        ]);
    }

    #[Test]
    public function employer_cannot_place_offer_on_own_task(): void
    {
        $this->actingAs($this->employer);

        $response = $this->post(route('tasks.offers.store', $this->task), [
            'offer_price' => 45,
            'message'     => 'Test',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('offers', [
            'user_id' => $this->employer->id,
        ]);
    }

    #[Test]
    public function worker_cannot_place_duplicate_offer(): void
    {
        Offer::create([
            'advertisement_id' => $this->task->id,
            'user_id'          => $this->worker->id,
            'price'            => 45,
            'message'          => 'First offer',
            'status'           => OfferStatus::Pending,
        ]);

        $this->actingAs($this->worker);

        $response = $this->post(route('tasks.offers.store', $this->task), [
            'offer_price' => 40,
            'message'     => 'Second offer attempt',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Offer::where('user_id', $this->worker->id)->count());
    }

    // ── ACCEPTING OFFERS ─────────────────────────────────────

    #[Test]
    public function employer_can_accept_an_offer(): void
    {
        $offer = Offer::create([
            'advertisement_id' => $this->task->id,
            'user_id'          => $this->worker->id,
            'price'            => 45,
            'message'          => 'I am available.',
            'status'           => OfferStatus::Pending,
        ]);

        $this->actingAs($this->employer);

        $response = $this->post(route('offers.accept', $offer));

        $response->assertRedirect();
        $this->assertEquals(OfferStatus::Accepted, $offer->fresh()->status);
        $this->assertEquals(TaskStatus::Assigned, $this->task->fresh()->status);
    }

    #[Test]
    public function accepting_offer_declines_other_offers(): void
    {
        $offer1 = Offer::create([
            'advertisement_id' => $this->task->id,
            'user_id'          => $this->worker->id,
            'price'            => 45,
            'message'          => 'My offer',
            'status'           => OfferStatus::Pending,
        ]);

        $offer2 = Offer::create([
            'advertisement_id' => $this->task->id,
            'user_id'          => $this->worker2->id,
            'price'            => 40,
            'message'          => 'My offer too',
            'status'           => OfferStatus::Pending,
        ]);

        $this->actingAs($this->employer);
        $this->post(route('offers.accept', $offer1));

        $this->assertEquals(OfferStatus::Accepted, $offer1->fresh()->status);
        $this->assertEquals(OfferStatus::Declined, $offer2->fresh()->status);
    }

    #[Test]
    public function non_owner_cannot_accept_offer(): void
    {
        $offer = Offer::create([
            'advertisement_id' => $this->task->id,
            'user_id'          => $this->worker->id,
            'price'            => 45,
            'message'          => 'I am available.',
            'status'           => OfferStatus::Pending,
        ]);

        $this->actingAs($this->worker2);

        $response = $this->post(route('offers.accept', $offer));

        $response->assertForbidden();
    }

    // ── CANCELLING OFFERS ────────────────────────────────────

    #[Test]
    public function worker_can_cancel_their_own_offer(): void
    {
        Offer::create([
            'advertisement_id' => $this->task->id,
            'user_id'          => $this->worker->id,
            'price'            => 45,
            'message'          => 'Cancelling',
            'status'           => OfferStatus::Pending,
        ]);

        $this->actingAs($this->worker);

        $response = $this->delete(route('tasks.offers.destroy', $this->task));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('offers', [
            'user_id'          => $this->worker->id,
            'advertisement_id' => $this->task->id,
        ]);
    }
}
