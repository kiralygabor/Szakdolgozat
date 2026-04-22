<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Notification System Tests
 *
 * Validates the notification center: viewing notifications,
 * marking all as read, and ensuring proper JSON API responses.
 */
class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    // ── NOTIFICATION ACCESS ──────────────────────────────────

    #[Test]
    public function guest_cannot_access_notifications(): void
    {
        $response = $this->get(route('notifications'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_can_view_notifications(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('notifications'));

        $response->assertStatus(200);
    }

    // ── MARK ALL AS READ ─────────────────────────────────────

    #[Test]
    public function user_can_mark_all_notifications_as_read(): void
    {
        // Use a real but anonymized notification for testing
        $this->user->notify(new class extends \Illuminate\Notifications\Notification {
            public function via($notifiable) { return ['database']; }
            public function toArray($notifiable) { return []; }
        });

        $this->actingAs($this->user);

        $response = $this->postJson(route('notifications.mark-read'));

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertEquals(0, $this->user->unreadNotifications()->count());
    }

    #[Test]
    public function guest_cannot_mark_notifications_as_read(): void
    {
        $response = $this->postJson(route('notifications.mark-read'));

        $response->assertUnauthorized();
    }
}
