<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Utils\GeneratesAccountId;
use Illuminate\Contracts\Translation\HasLocalePreference;

class User extends Authenticatable implements HasLocalePreference
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the user's preferred locale.
     */
    public function preferredLocale(): string
    {
        return $this->locale ?? config('app.locale');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
         'first_name',
         'last_name',
         'birthdate',
         'phone_number',
         'email',
         'password',
         'account_id',
         'city_id',
         'avatar',
         'google_id',
         'verified',
         'email_notifications',
         'email_task_digest',
         'email_direct_quotes',
         'locale',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthdate' => 'date:Y-m-d',
            'password' => 'hashed',
            'email_notifications' => 'boolean',
            'email_task_digest' => 'boolean',
            'email_direct_quotes' => 'boolean',
        ];
    }
     public function city()
    {
        return $this->belongsTo(City::class);
    }


     public function verifyUser()
    {
        return $this->hasOne(VerifyUser::class);
    }
    public function category() {
        return $this->hasOne(Category::class);
    }
    public function advertisements()
    {
        return $this->hasMany(Advertisement::class, 'employer_id', 'id');
    }

    public function trackedCategories()
    {
        return $this->belongsToMany(Category::class, 'tracked_categories')
            ->withPivot('last_digest_sent_at')
            ->withTimestamps();
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'target_user_id');
    }

    public function getRatingAttribute()
    {
        $avg = $this->reviewsReceived()->avg('stars');
        return $avg ? round($avg, 1) : 0;
    }

    /**
     * Get the full URL for the user's avatar.
     */
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

        // If it's a storage path (like 'avatars/xxx.png'), use Storage::url
        return asset('storage/' . ltrim($this->avatar, '/'));
    }
}
