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
        return view('auth.registration');
    }

    public function registration_settings(): View
    {
        return view('auth.registration_settings');
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
                    ->with('status', 'Enter the verification code we sent to your email.');
            }

            return redirect()->intended('mainpage')
                ->withSuccess('You have successfully logged in.');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    /**
     * Handle registration request
     */
    public function postRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        session(['registration_email' => $user->email]);

        // Create verification token
        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token'   => rand(100000, 999999),
        ]);

        // Send email
        Mail::to($user->email)->send(new VerifyMail($user, $verifyUser->token));

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
            'birthdate'     => 'required|date',
            'phone_number'  => 'required|string|max:20',
            'county'        => 'nullable|string|max:100',
            'city'          => 'nullable|string|max:100',
        ]);

        $user = User::where('email', session('registration_email'))->first();

        if (!$user) {
            return redirect('registration')->with('error', 'You must register first.');
        }

        $user->fill($validated);
        $user->save();

        session()->forget('registration_email');

        session(['pending_user_id' => $user->id]);

        return redirect()->route('verify.code.form')
            ->with('status', 'We sent you a verification code. Check your email.');
    }

    /**
     * Show verify code page
     */
    public function showVerifyCodeForm(): View|RedirectResponse
{
    
    if (!session('pending_user_id')) {
        return redirect()->route('login')
            ->withErrors(['email' => 'Session expired. Please login again.']);
    }

    $user = User::find(session('pending_user_id'));

    if (!$user) {
        return redirect()->route('login')
            ->withErrors(['email' => 'User not found.']);
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
            return back()->withErrors(['code' => 'Session expired. Please login again.']);
        }

        $verifyUser = VerifyUser::where('user_id', $userId)
            ->where('token', $request->code)
            ->first();

        if (!$verifyUser) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        $user = $verifyUser->user;
        $user->verified = true;
        $user->save();

        session()->forget('pending_user_id');

        return redirect('/login')->with('status', 'Your account has been verified. Please login now.');
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

        Mail::to($user->email)->send(new VerifyMail($user, $verifyUser->token));

        return back()->with('status', 'A new verification code has been sent.');
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
