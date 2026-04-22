<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $table = 'advertisement_reports';

    const UPDATED_AT = null;

    protected $fillable = [
        'advertisement_id',
        'description',
        'reporter_account_id',
        'reported_account_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_account_id');
    }

    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_account_id');
    }
}
