<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementView extends Model
{
    protected $fillable = [
        'advertisement_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
