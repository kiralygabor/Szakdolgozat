<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $email
 */
class User extends Authenticatable implements HasLocalePreference
{
    use HasFactory, Notifiable;

    protected $fillable = [
         'first_name',
         'last_name',
         'birthdate',
         'phone_number',
         'email',
         'account_id',
         'city_id',
         'avatar',
         'google_id',
         'facebook_id',
         'email_notifications',
         'email_task_digest',
         'email_direct_quotes',
         'locale',
         'theme',
         'reduced_motion',
         'high_contrast',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthdate' => 'date:Y-m-d',
            'verified' => 'boolean',
            'password' => 'hashed',
            'email_notifications' => 'boolean',
            'email_task_digest' => 'boolean',
            'email_direct_quotes' => 'boolean',
            'reduced_motion' => 'boolean',
            'high_contrast' => 'boolean',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token, app()->getLocale()));
    }

    public function preferredLocale(): string
    {
        return $this->locale ?? config('app.locale');
    }

    // ── Domain Helpers ────────────────────────────────────────

    public function hasIncompleteProfile(): bool
    {
        return empty($this->avatar) || empty($this->city_id);
    }

    public function getMissingProfileSteps(): array
    {
        $steps = [];
        
        if (empty($this->avatar) || str_contains($this->avatar, 'default.jpg')) {
            $steps[] = ['text' => __('tasks_page.missing_steps.picture', [], $this->preferredLocale()), 'icon' => 'user'];
        }
        if (empty($this->birthdate)) {
            $steps[] = ['text' => __('tasks_page.missing_steps.birthdate', [], $this->preferredLocale()), 'icon' => 'calendar'];
        }
        if (empty($this->phone_number)) {
            $steps[] = ['text' => __('tasks_page.missing_steps.mobile', [], $this->preferredLocale()), 'icon' => 'smartphone'];
        }
        if (empty($this->city_id)) {
            $steps[] = ['text' => __('tasks_page.missing_steps.location', [], $this->preferredLocale()), 'icon' => 'map-pin'];
        }

        return $steps;
    }

    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return asset('assets/img/default.jpg');
        }

        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        if (str_starts_with($this->avatar, 'assets/')) {
            return asset($this->avatar);
        }

        return url('storage/' . ltrim($this->avatar, '/'));
    }

    public function getRatingAttribute(): float
    {
        $average = $this->reviewsReceived()->avg('stars');

        return $average ? round($average, 1) : 0;
    }

    // ── Relationships ────────────────────────────────────────

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function verifyUser(): HasOne
    {
        return $this->hasOne(VerifyUser::class);
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class, 'employer_id', 'id');
    }

    public function trackedCategories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'tracked_categories')
            ->withPivot('last_digest_sent_at')
            ->withTimestamps();
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'target_user_id');
    }
}
