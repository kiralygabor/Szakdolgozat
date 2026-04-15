<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Page Accessibility Tests
 *
 * Validates that all public-facing pages render without errors (HTTP 200)
 * and that all authenticated pages are properly guarded behind login.
 * This ensures no broken routes or missing views exist in the application.
 */
class PageAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    // ── PUBLIC PAGES (No Login Required) ─────────────────────

    #[Test]
    public function home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    #[Test]
    public function index_page_loads_successfully(): void
    {
        $response = $this->get(route('index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function how_it_works_page_loads_successfully(): void
    {
        $response = $this->get(route('howitworks'));

        $response->assertStatus(200);
    }

    #[Test]
    public function tasks_browse_page_loads_successfully(): void
    {
        $response = $this->get(route('tasks'));

        $response->assertStatus(200);
    }

    #[Test]
    public function category_page_loads_successfully(): void
    {
        $response = $this->get(route('category'));

        $response->assertStatus(200);
    }

    #[Test]
    public function login_page_loads_successfully(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    #[Test]
    public function registration_page_loads_successfully(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    #[Test]
    public function terms_and_conditions_page_loads_successfully(): void
    {
        $response = $this->get(route('terms'));

        $response->assertStatus(200);
    }

    #[Test]
    public function privacy_policy_page_loads_successfully(): void
    {
        $response = $this->get(route('privacy'));

        $response->assertStatus(200);
    }

    #[Test]
    public function community_guidelines_page_loads_successfully(): void
    {
        $response = $this->get(route('guidelines'));

        $response->assertStatus(200);
    }

    #[Test]
    public function help_faq_page_loads_successfully(): void
    {
        $response = $this->get(route('help-faq'));

        $response->assertStatus(200);
    }

    #[Test]
    public function contact_support_page_loads_successfully(): void
    {
        $response = $this->get(route('contact-support'));

        $response->assertStatus(200);
    }

    #[Test]
    public function public_profile_page_loads_for_valid_user(): void
    {
        $response = $this->get(route('public-profile', $this->user->id));

        $response->assertStatus(200);
    }

    #[Test]
    public function forgot_password_page_loads_successfully(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    // ── AUTHENTICATED PAGES (Login Required) ─────────────────

    #[Test]
    public function my_tasks_requires_authentication(): void
    {
        $response = $this->get(route('my-tasks'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function notifications_requires_authentication(): void
    {
        $response = $this->get(route('notifications'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function profile_editor_requires_authentication(): void
    {
        $response = $this->get(route('profile'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function messages_requires_authentication(): void
    {
        $response = $this->get(route('messages'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function post_task_requires_authentication(): void
    {
        $response = $this->get(route('post-task'));

        $response->assertRedirect(route('login'));
    }

    // ── AUTHENTICATED PAGES (Successful Access) ──────────────

    #[Test]
    public function authenticated_user_can_access_my_tasks(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('my-tasks'));

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_access_notifications(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('notifications'));

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_access_messages(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('messages'));

        $response->assertStatus(200);
    }

    // ── LEGACY REDIRECTS ─────────────────────────────────────

    #[Test]
    public function legacy_advertisements_url_redirects_to_tasks(): void
    {
        $response = $this->get('/advertisements');

        $response->assertRedirect(route('tasks'));
    }
}
