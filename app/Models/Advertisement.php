<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Advertisement extends Model
{
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
        'views',
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
        'is_direct' => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────

    public function category(): HasOneThrough
    {
        return $this->hasOneThrough(Category::class, Job::class, 'id', 'id', 'jobs_id', 'categories_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'jobs_id');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'advertisement_id');
    }

    public function distinctViews(): HasMany
    {
        return $this->hasMany(AdvertisementView::class, 'advertisement_id');
    }

    // ── Query Scopes ─────────────────────────────────────────

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::Open)->whereNull('employee_id');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // ── Domain Helpers (avoid negative conditionals) ─────────

    public function isOwner(int $userId): bool
    {
        return $this->employer_id === $userId;
    }

    public function isOpen(): bool
    {
        return $this->status === TaskStatus::Open->value;
    }

    public function hasEmployee(): bool
    {
        return $this->employee_id !== null;
    }
}