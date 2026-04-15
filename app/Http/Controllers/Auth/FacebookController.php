<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook(): RedirectResponse
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            $existingUser = User::where('facebook_id', $socialUser->id)->first();

            if ($existingUser) {
                Auth::login($existingUser);

                return redirect()->intended(route('index'));
            }

            $nameParts = explode(' ', $socialUser->getName(), 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $newUser = new User([
                'first_name'  => $firstName,
                'last_name'   => $lastName,
                'email'       => $socialUser->email,
                'facebook_id' => $socialUser->id,
                'account_id'  => self::generateUniqueAccountId(),
            ]);
            $newUser->password = Hash::make(Str::random(16));
            $newUser->verified = true;
            $newUser->save();

            Auth::login($newUser);

            return redirect()->intended(route('index'));
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', __('Facebook login failed. Please try again.'));
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
