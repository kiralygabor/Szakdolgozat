<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;
use App\Models\VerifyUser;
use App\Models\County;
use App\Mail\VerifyMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function index(): View
    {
        return view('auth.login');
    }

    /**
     * Show registration form
     */
    public function registration(): View
    {
        $prefill = session('registration_form', []);
        return view('auth.registration', compact('prefill'));
    }

    public function registration_settings(): View
    {
        $counties = \App\Models\County::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('auth.registration_settings', compact('counties', 'categories'));
    }

    /**
     * Handle login request
     */
    public function postLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('rememberMe'); // true if checked

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Block unverified users
            if (!$user->verified) {
                Auth::logout();

                // store user ID for later verification
                session(['pending_user_id' => $user->id]);

                return redirect()->route('verify.code.form')
                    ->with('status', __('auth_pages.status.verify_code_sent'));
            }

            // Check for returnUrl in the request
            $returnUrl = $request->input('returnUrl');
            if ($returnUrl) {
                return redirect($returnUrl)->withSuccess(__('auth_pages.status.login_success'));
            }

            return redirect()->intended(route('tasks'))
                ->withSuccess(__('auth_pages.status.login_success'));
        }

        return back()->withErrors(['email' => __('auth_pages.errors.invalid_credentials')]);
    }

    /**
     * Handle registration request
     */
    public function postRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // If email already exists, attempt to log in instead
        if (User::where('email', $request->email)->exists()) {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();

                if (!$user->verified) {
                    Auth::logout();
                    session(['pending_user_id' => $user->id]);
                    return redirect()->route('verify.code.form')
                        ->with('status', __('auth_pages.status.email_already_registered'));
                }

                return redirect()->intended(route('tasks'))
                    ->withSuccess(__('auth_pages.status.email_in_use_logged_in'));
            }

            return back()->withInput()->withErrors(['email' => __('auth_pages.errors.email_in_use')]);
        }

        session(['registration_form' => [
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]]);

        return redirect('registration_settings');
    }

    protected function create(array $data): User
    {
        return User::create([
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Registration settings
     */
    public function postRegistrationSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'birthdate'     => 'nullable|date',
            'phone_number'  => 'nullable|string|max:20',
            'county_id'     => 'required|integer|exists:counties,id',
            'city_id'       => 'required|integer|exists:cities,id',
        ]);
        $registration = session('registration_form');

        if (!$registration || empty($registration['email']) || empty($registration['password'])) {
            return redirect('registration')->with('error', __('auth_pages.errors.complete_step_1'));
        }

        // Ensure email is still unique in case something changed meanwhile
        if (User::where('email', $registration['email'])->exists()) {
            return redirect('registration')->withErrors(['email' => __('auth_pages.errors.email_taken')]);
        }

        // Generate a unique account ID (AC prefix + random digits)
        do {
            $accountId = 'AC' . str_pad((string)mt_rand(1, 999999), 4, '0', STR_PAD_LEFT);
        } while (User::where('account_id', $accountId)->exists());

        $user = User::create([
            'email'           => $registration['email'],
            'password'        => Hash::make($registration['password']),
            'account_id'      => $accountId,
            'first_name'      => $validated['first_name'],
            'last_name'       => $validated['last_name'],
            'birthdate'       => $validated['birthdate'] ?? null,
            'phone_number'    => $validated['phone_number'] ?? null,
            'city_id'         => (int) $validated['city_id'],
            'avatar'          => 'assets/img/default.jpg',
            'email_notifications' => $request->has('email_notifications'),
            'email_task_digest' => $request->has('email_task_digest'),
            'locale'          => session('locale', config('app.locale', 'en')),
        ]);

        // Sync tracked categories if opted in
        if ($request->has('email_task_digest') && $request->has('tracked_categories')) {
            $user->trackedCategories()->sync($request->input('tracked_categories'));
        }

        // Create verification token
        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token'   => rand(100000, 999999),
        ]);

        // Send verification email
        Mail::to($user->email)->locale($user->preferredLocale())->send(new VerifyMail($user, $verifyUser->token));

        // Clear registration data from session
        session()->forget('registration_form');

        session(['pending_user_id' => $user->id]);

        return redirect()->route('verify.code.form')
            ->with('status', __('auth_pages.status.verification_sent'));
    }

    /**
     * Show verify code page
     */
    public function showVerifyCodeForm(): View|RedirectResponse
{
    
    if (!session('pending_user_id')) {
        return redirect()->route('login')
            ->withErrors(['email' => __('auth_pages.errors.session_expired')]);
    }

    $user = User::find(session('pending_user_id'));

    if (!$user) {
        return redirect()->route('login')
            ->withErrors(['email' => __('auth_pages.errors.user_not_found')]);
    }

    return view('auth.verify-code', compact('user'));
}

    /**
     * Verify code
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $userId = session('pending_user_id');
        if (!$userId) {
            return back()->withErrors(['code' => __('auth_pages.errors.session_expired')]);
        }

        $verifyUser = VerifyUser::where('user_id', $userId)
            ->where('token', $request->code)
            ->first();

        if (!$verifyUser) {
            return back()->withErrors(['code' => __('auth_pages.errors.invalid_code')]);
        }

        $user = $verifyUser->user;
        $user->verified = true;
        $user->save();

        session()->forget('pending_user_id');

        return redirect('/login')->with('status', __('auth_pages.status.account_verified'));
    }

    /**
     * Resend verification code
     */
    public function resendCode(): RedirectResponse
    {
        $userId = session('pending_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }

        $verifyUser = VerifyUser::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => rand(100000, 999999)]
        );

        Mail::to($user->email)->locale($user->preferredLocale())->send(new VerifyMail($user, $verifyUser->token));

        return back()->with('status', __('auth_pages.status.new_code_sent'));
    }

    /**
     * Forgot password + reset methods
     */
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
            'password' => 'required|min:6|confirmed',
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

    

    /**
     * Logout
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();

        return redirect('index');
    }
}
