<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended(route('index'));
            } else {
                // Generate a unique account ID (AC prefix + random digits)
                do {
                    $accountId = 'AC' . str_pad((string)mt_rand(1, 999999), 4, '0', STR_PAD_LEFT);
                } while (User::where('account_id', $accountId)->exists());

                // Split name or use raw data if available
                $firstName = $user->offsetGet('given_name') ?? $user->getName();
                $lastName = $user->offsetGet('family_name') ?? '';

                $newUser = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'account_id' => $accountId,
                    'verified' => true,
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16))
                ]);

                Auth::login($newUser);
                return redirect()->intended(route('index'));
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}