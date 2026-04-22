<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\User $socialUser */
            $socialUser = Socialite::driver('google')->user();
            $existingUser = User::where('google_id', $socialUser->id)->first();

            if ($existingUser) {
                Auth::login($existingUser);

                return redirect()->intended(route('index'));
            }

            $rawUser = $socialUser->user;
            $firstName = $rawUser['given_name'] ?? $socialUser->getName();
            $lastName = $rawUser['family_name'] ?? '';

            $newUser = new User([
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $socialUser->email,
                'google_id'  => $socialUser->id,
                'account_id' => self::generateUniqueAccountId(),
            ]);
            $newUser->password = Hash::make(Str::random(16));
            $newUser->verified = true;
            $newUser->save();

            Auth::login($newUser);

            return redirect()->intended(route('index'));
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', __('Google login failed. Please try again.'));
        }
    }

    private static function generateUniqueAccountId(): string
    {
        do {
            $accountId = 'AC' . str_pad((string) mt_rand(1, 999999), 4, '0', STR_PAD_LEFT);
        } while (User::where('account_id', $accountId)->exists());

        return $accountId;
    }
}