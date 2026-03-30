<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Advertisement extends Model
{
    protected $table = 'advertisements';
    // Use Eloquent timestamps (created_at/updated_at)

    protected $fillable = [
        'reviews_id',
        'employer_id',
        'employee_id',
        'title',
        'description',
        'price',
        'location',
        'expiration_date',
        'status',
        'required_date',
        'required_before_date',
        'is_date_flexible',
        'preferred_time',
        'task_type',
        'photos',
        'jobs_id',
        'is_direct',
        'views'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'expiration_date' => 'datetime',
        'required_date' => 'date',
        'required_before_date' => 'date',
        'price' => 'integer',
        'is_date_flexible' => 'boolean',
        'photos' => 'array',
        'preferred_time' => 'array',
        'is_direct' => 'boolean'
    ];

    public function category()
    {
        return $this->hasOneThrough(Category::class, Job::class, 'id', 'id', 'jobs_id', 'categories_id');
    }
 
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
 
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'jobs_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'advertisement_id');
    }

    public function distinctViews()
    {
        return $this->hasMany(AdvertisementView::class, 'advertisement_id');
    }
}