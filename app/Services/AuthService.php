<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerifyUser;
use App\Mail\VerifyMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    private const ACCOUNT_ID_PREFIX = 'AC';
    private const ACCOUNT_ID_MAX = 999999;
    private const ACCOUNT_ID_PAD_LENGTH = 4;
    private const VERIFICATION_CODE_MIN = 100000;
    private const VERIFICATION_CODE_MAX = 999999;
    private const VERIFICATION_CODE_EXPIRY_MINUTES = 15;
    private const DEFAULT_AVATAR = 'assets/img/default.jpg';

    /**
     * Register a new user and send verification mail.
     */
    public function registerUser(array $registrationData, array $validatedSettings, bool $emailNotifications, bool $emailTaskDigest, ?array $trackedCategories): User
    {
        $accountId = $this->generateUniqueAccountId();

        $user = new User([
            'email'               => $registrationData['email'],
            'account_id'          => $accountId,
            'first_name'          => $validatedSettings['first_name'],
            'last_name'           => $validatedSettings['last_name'],
            'birthdate'           => $validatedSettings['birthdate'] ?? null,
            'phone_number'        => $validatedSettings['phone_number'] ?? null,
            'city_id'             => (int) $validatedSettings['city_id'],
            'avatar'              => self::DEFAULT_AVATAR,
            'email_notifications' => $emailNotifications,
            'email_task_digest'   => $emailTaskDigest,
            'locale'              => session('locale', config('app.locale', 'en')),
        ]);

        // Password is already hashed from session — set directly to bypass $fillable
        $user->password = $registrationData['password_hash'];
        $user->save();

        if ($emailTaskDigest && $trackedCategories) {
            $user->trackedCategories()->sync($trackedCategories);
        }

        $this->sendVerificationCode($user);

        return $user;
    }

    /**
     * Generate and send a verification code to the user.
     */
    public function sendVerificationCode(User $user): void
    {
        $verificationCode = $this->generateVerificationCode();
        
        VerifyUser::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $verificationCode]
        );

        Mail::to($user->email)->locale($user->preferredLocale())->send(new VerifyMail($user, $verificationCode));
    }

    /**
     * Verify a user's account with a code.
     */
    public function verifyUser(User $user, string $code): bool
    {
        $verifyUser = VerifyUser::where('user_id', $user->id)
            ->where('token', $code)
            ->first();

        if (!$verifyUser) {
            return false;
        }

        // Reject expired codes (15-minute window)
        $codeAge = $verifyUser->updated_at ?? $verifyUser->created_at;
        if ($codeAge && $codeAge->diffInMinutes(now()) > self::VERIFICATION_CODE_EXPIRY_MINUTES) {
            return false;
        }

        $user->verified = true;
        $user->save();

        $verifyUser->delete();

        return true;
    }

    /**
     * Generate a unique account ID.
     */
    private function generateUniqueAccountId(): string
    {
        do {
            $accountId = self::ACCOUNT_ID_PREFIX . str_pad(
                (string) mt_rand(1, self::ACCOUNT_ID_MAX),
                self::ACCOUNT_ID_PAD_LENGTH,
                '0',
                STR_PAD_LEFT
            );
        } while (User::where('account_id', $accountId)->exists());

        return $accountId;
    }

    /**
     * Generate a random verification code.
     */
    private function generateVerificationCode(): int
    {
        return random_int(self::VERIFICATION_CODE_MIN, self::VERIFICATION_CODE_MAX);
    }
}
