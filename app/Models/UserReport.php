<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReport extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'description',
        'reporter_account_id',
        'reported_account_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_account_id', 'account_id');
    }

    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_account_id', 'account_id');
    }
}
