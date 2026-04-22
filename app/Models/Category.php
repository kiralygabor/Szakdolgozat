<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    protected $fillable = ['name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'categories_id');
    }

    public function advertisements(): HasManyThrough
    {
        return $this->hasManyThrough(
            Advertisement::class,
            Job::class,
            'categories_id',
            'jobs_id',
            'id',
            'id'
        );
    }
}
