<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Models\County;
use App\Models\User;
use App\Models\VerifyUser;
use App\Mail\VerifyMail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected \App\Services\AuthService $authService
    ) {}

    public function index(): View
    {
        return view('auth.login');
    }

    public function registration(): View
    {
        $prefill = session('registration_form', []);

        return view('auth.registration', compact('prefill'));
    }

    public function registrationSettings(): View
    {
        $counties = County::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('auth.registration_settings', compact('counties', 'categories'));
    }

    public function postLogin(\App\Http\Requests\Auth\LoginEmailRequest $request): RedirectResponse
    {

        $credentials = $request->only('email', 'password');
        $remember = $request->has('rememberMe');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => __('auth_pages.errors.invalid_credentials')]);
        }

        $user = Auth::user();

        if (!$user->verified) {
            Auth::logout();
            session(['pending_user_id' => $user->id]);

            return redirect()->route('verify.code.form')
                ->with('status', __('auth_pages.status.verify_code_sent'));
        }

        // Regenerate session ID to prevent session fixation attacks
        $request->session()->regenerate();

        return redirect()->route('index')
            ->withSuccess(__('auth_pages.status.login_success'));
    }

    public function postRegistration(\App\Http\Requests\Auth\RegistrationStep1Request $request): RedirectResponse
    {

        if (!User::where('email', $request->email)->exists()) {
            // Store hashed password in session to avoid plaintext exposure
            session(['registration_form' => [
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
            ]]);

            return redirect('registration_settings');
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if (!$user->verified) {
                Auth::logout();
                session(['pending_user_id' => $user->id]);

                return redirect()->route('verify.code.form')
                    ->with('status', __('auth_pages.status.email_already_registered'));
            }

            return redirect()->route('index')
                ->withSuccess(__('auth_pages.status.email_in_use_logged_in'));
        }

        return back()->withInput()->withErrors(['email' => __('auth_pages.errors.email_in_use')]);
    }

    public function postRegistrationSettings(\App\Http\Requests\Auth\RegistrationSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $registration = session('registration_form');

        $hasMissingRegistrationData = !$registration || empty($registration['email']) || empty($registration['password_hash']);
        if ($hasMissingRegistrationData) {
            return redirect('registration')->with('error', __('auth_pages.errors.complete_step_1'));
        }

        if (User::where('email', $registration['email'])->exists()) {
            return redirect('registration')->withErrors(['email' => __('auth_pages.errors.email_taken')]);
        }

        $user = $this->authService->registerUser(
            $registration,
            $validated,
            $request->has('email_notifications'),
            $request->has('email_task_digest'),
            $request->input('tracked_categories')
        );

        session()->forget('registration_form');
        session(['pending_user_id' => $user->id]);

        return redirect()->route('verify.code.form')
            ->with('status', __('auth_pages.status.verification_sent'));
    }

    public function showVerifyCodeForm(): View|RedirectResponse
    {
        $pendingUserId = session('pending_user_id');

        if (!$pendingUserId) {
            return redirect()->route('login')
                ->withErrors(['email' => __('auth_pages.errors.session_expired')]);
        }

        $user = User::find($pendingUserId);

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => __('auth_pages.errors.user_not_found')]);
        }

        return view('auth.verify-code', compact('user'));
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $userId = session('pending_user_id');
        if (!$userId) {
            return back()->withErrors(['code' => __('auth_pages.errors.session_expired')]);
        }

        $user = User::findOrFail($userId);

        if (!$this->authService->verifyUser($user, $request->code)) {
            return back()->withErrors(['code' => __('auth_pages.errors.invalid_code')]);
        }

        session()->forget('pending_user_id');

        return redirect('/login')->with('status', __('auth_pages.status.account_verified'));
    }

    public function resendCode(): RedirectResponse
    {
        $userId = session('pending_user_id');
        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['email' => __('auth_pages.errors.session_expired')]);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => __('auth_pages.errors.user_not_found')]);
        }

        $this->authService->sendVerificationCode($user);

        return back()->with('status', __('auth_pages.status.new_code_sent'));
    }

    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('index');
    }

}
