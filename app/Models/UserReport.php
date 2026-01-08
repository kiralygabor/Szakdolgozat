<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    // Only use created_at timestamp
    const UPDATED_AT = null;

    protected $fillable = [
        'description',
        'reporter_account_id',
        'reported_account_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_account_id', 'account_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_account_id', 'account_id');
    }
}
