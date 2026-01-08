<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'advertisements_reports';

    // Only use created_at timestamp
    const UPDATED_AT = null;

    protected $fillable = [
        'advertisement_id',
        'description',
        'reporter_account_id',
        'reported_account_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisment::class, 'advertisement_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_account_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_account_id');
    }
}
