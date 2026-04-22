<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VerifyUser;
use App\Models\County;
use App\Models\City;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

/**
 * Authentication Tests
 * 
 * Validates the complete user authentication lifecycle:
 * login, registration, email verification, logout, and password reset.
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private User $verifiedUser;
    private User $unverifiedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->verifiedUser = User::factory()->create([
            'email'    => 'verified@example.com',
            'password' => Hash::make('Password1'),
            'verified' => true,
        ]);

        $this->unverifiedUser = User::factory()->create([
            'email'    => 'unverified@example.com',
            'password' => Hash::make('Password1'),
            'verified' => false,
        ]);
    }

    // ── LOGIN ────────────────────────────────────────────────

    #[Test]
    public function login_page_renders_successfully(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    #[Test]
    public function verified_user_can_log_in_with_valid_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'email'    => 'verified@example.com',
            'password' => 'Password1',
        ]);

        $response->assertRedirect(route('index'));
        $this->assertAuthenticatedAs($this->verifiedUser);
    }

    #[Test]
    public function login_fails_with_invalid_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'email'    => 'verified@example.com',
            'password' => 'WrongPassword1',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    #[Test]
    public function unverified_user_is_redirected_to_verification_page(): void
    {
        $response = $this->post(route('login.post'), [
            'email'    => 'unverified@example.com',
            'password' => 'Password1',
        ]);

        $response->assertRedirect(route('verify.code.form'));
        $this->assertGuest();
    }

    // ── REGISTRATION ─────────────────────────────────────────

    #[Test]
    public function registration_page_renders_successfully(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.registration');
    }

    #[Test]
    public function new_user_can_start_registration_with_valid_email(): void
    {
        $response = $this->post(route('register.post'), [
            'email'                 => 'newuser@example.com',
            'password'              => 'Password1',
            'password_confirmation' => 'Password1',
        ]);

        $response->assertRedirect('registration_settings');
        $this->assertEquals('newuser@example.com', session('registration_form.email'));
    }

    #[Test]
    public function registration_rejects_duplicate_email(): void
    {
        $response = $this->post(route('register.post'), [
            'email'                 => 'verified@example.com',
            'password'              => 'WrongPassword99',
            'password_confirmation' => 'WrongPassword99',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // ── EMAIL VERIFICATION ───────────────────────────────────

    #[Test]
    public function valid_verification_code_activates_account(): void
    {
        session(['pending_user_id' => $this->unverifiedUser->id]);

        VerifyUser::create([
            'user_id' => $this->unverifiedUser->id,
            'token'   => '123456',
        ]);

        $response = $this->post(route('verify.code'), ['code' => '123456']);

        $response->assertRedirect('/login');
        $this->assertTrue($this->unverifiedUser->fresh()->verified);
    }

    #[Test]
    public function invalid_verification_code_is_rejected(): void
    {
        session(['pending_user_id' => $this->unverifiedUser->id]);

        VerifyUser::create([
            'user_id' => $this->unverifiedUser->id,
            'token'   => '123456',
        ]);

        $response = $this->post(route('verify.code'), ['code' => '999999']);

        $response->assertSessionHasErrors('code');
        $this->assertFalse($this->unverifiedUser->fresh()->verified);
    }

    // ── LOGOUT ───────────────────────────────────────────────

    #[Test]
    public function authenticated_user_can_log_out(): void
    {
        $this->actingAs($this->verifiedUser);

        $response = $this->post(route('logout'));

        $response->assertRedirect('index');
        $this->assertGuest();
    }

    // ── PASSWORD RESET ───────────────────────────────────────

    #[Test]
    public function forgot_password_page_renders_successfully(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }
}
