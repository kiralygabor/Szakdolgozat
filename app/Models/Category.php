<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     protected $fillable = [
         'id',
         'name',

    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function jobs() {
        return $this->hasMany(Job::class, 'categories_id');
    }

    public function advertisements()
    {
        return $this->hasManyThrough(
            Advertisement::class,
            Job::class,
            'categories_id', // Foreign key on jobs table...
            'jobs_id',       // Foreign key on advertisements table...
            'id',            // Local key on categories table...
            'id'             // Local key on jobs table...
        );
    }
}
