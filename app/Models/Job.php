<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Job extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'categories_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
    //
}
