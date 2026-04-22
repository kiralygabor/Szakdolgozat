<?php
 
namespace Tests\Feature;
 
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
 
/**
 * Profile Management Tests
 *
 * Validates user profile operations: viewing, updating personal info,
 * uploading avatars, managing notification preferences, and account deletion.
 * Ensures proper authorization and data persistence.
 */
class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;
 
    private User $user;
 
    protected function setUp(): void
    {
        parent::setUp();
 
        Storage::fake('public');
 
        $this->user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }
 
    // ── PROFILE VIEWING ──────────────────────────────────────
 
    #[Test]
    public function guest_cannot_access_profile_page(): void
    {
        $response = $this->get(route('profile'));
 
        $response->assertRedirect(route('login'));
    }
 
    #[Test]
    public function authenticated_user_can_view_their_profile(): void
    {
        $this->actingAs($this->user);
 
        $response = $this->get(route('profile'));
 
        $response->assertStatus(200);
        $response->assertSee('John');
    }
 
    // ── PROFILE UPDATING ─────────────────────────────────────
 
    #[Test]
    public function user_can_update_their_name(): void
    {
        $this->actingAs($this->user);
 
        $response = $this->put(route('profile.update'), [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => $this->user->email,
        ]);
 
        $response->assertRedirect();
        $this->assertEquals('Jane', $this->user->fresh()->first_name);
        $this->assertEquals('Smith', $this->user->fresh()->last_name);
    }
 
    #[Test]
    public function user_cannot_update_profile_to_incomplete_state(): void
    {
        $this->actingAs($this->user);
 
        $response = $this->put(route('profile.update'), [
            'first_name' => '',
            'last_name' => 'Doe',
            'email' => 'invalid-email',
        ]);
 
        $response->assertSessionHasErrors(['first_name', 'email']);
    }
 
    #[Test]
    public function user_can_upload_a_profile_avatar(): void
    {
        $this->actingAs($this->user);
 
        $file = UploadedFile::fake()->create('avatar.jpg', 100);
 
        $response = $this->put(route('profile.update'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $this->user->email,
            'avatar' => $file,
        ]);
 
        $response->assertRedirect();
 
        $updatedUser = $this->user->fresh();
        $this->assertNotNull($updatedUser->avatar);
        $this->assertTrue(Storage::disk('public')->exists($updatedUser->avatar));
    }
 
    // ── NOTIFICATION SETTINGS ────────────────────────────────
 
    #[Test]
    public function user_can_toggle_email_notification_preferences(): void
    {
        $this->actingAs($this->user);
 
        $response = $this->put(route('profile.notifications.update'), [
            'email_notifications' => true,
        ]);
 
        $response->assertRedirect();
        $this->assertTrue($this->user->fresh()->email_notifications);
    }
 
    // ── PUBLIC PROFILE ───────────────────────────────────────
 
    #[Test]
    public function public_profile_is_accessible_by_anyone(): void
    {
        $response = $this->get(route('public-profile', $this->user->id));
 
        $response->assertStatus(200);
        $response->assertSee('John');
    }
}