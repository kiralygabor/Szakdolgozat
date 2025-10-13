<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Advertisment extends Model
{
    protected $table = 'advertisments';
    public $timestamps = false; // migration defines created_at manually
 
    protected $fillable = [
        'categories_id',
        'reviews_id',
        'employer_id',
        'employee_id',
        'title',
        'description',
        'price',
        'created_at',
        'expiration_date',
        'status',
    ];
 
    protected $casts = [
        'created_at' => 'datetime',
        'expiration_date' => 'datetime',
        'price' => 'integer',
    ];
 
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
 
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
 
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}