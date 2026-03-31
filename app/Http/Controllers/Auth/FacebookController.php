<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended(route('index'));
            } else {
                // Generate a unique account ID (AC prefix + random digits)
                do {
                    $accountId = 'AC' . str_pad((string)mt_rand(1, 999999), 4, '0', STR_PAD_LEFT);
                } while (User::where('account_id', $accountId)->exists());

                // Split name (Facebook provides full name, so we split it)
                $fullName = $user->getName();
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? $fullName;
                $lastName = $nameParts[1] ?? '';

                $newUser = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $user->email,
                    'facebook_id'=> $user->id,
                    'account_id' => $accountId,
                    'verified' => true,
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16))
                ]);

                Auth::login($newUser);
                return redirect()->intended(route('index'));
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Facebook login failed: ' . $e->getMessage());
        }
    }
}
