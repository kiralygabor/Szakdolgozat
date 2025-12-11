<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Utils\GeneratesAccountId;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
         'subscription_id',
         'avatar',
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
        ];
    }
     public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
     public function verifyUser()
    {
        return $this->hasOne(VerifyUser::class);
    }
    public function category() {
        return $this->hasOne(Category::class);
    }
        public function advertisments()
    {
        return $this->hasMany(Advertisment::class, 'employer', 'id');
    }

    public function getRatingAttribute()
    {
        // TODO: Implement real rating logic when reviews are linked
        return 4.9; 
    }
}
