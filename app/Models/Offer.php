<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    protected $fillable = [
        'advertisement_id',
        'user_id',
        'price',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => \App\Enums\OfferStatus::class,
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
