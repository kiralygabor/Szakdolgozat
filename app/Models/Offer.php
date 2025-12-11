<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    protected $fillable = [
        'advertisment_id',
        'user_id',
        'price',
        'message',
        'status',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Advertisment::class, 'advertisment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
