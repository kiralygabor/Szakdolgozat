<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $employer_id
 * @property int|null $employee_id
 * @property TaskStatus $status
 */
class Advertisement extends Model
{
    use SoftDeletes;

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
        'status' => TaskStatus::class,
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

    public function scopeApplyFilters(Builder $query, ?string $term, int $minPrice, int $maxPrice): Builder
    {
        if (!empty($term)) {
            $query->where(function (Builder $q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function scopeForCategory(Builder $query, ?int $categoryId): Builder
    {
        if (!$categoryId) {
            return $query;
        }

        return $query->whereHas('job', fn($sub) => $sub->where('categories_id', $categoryId));
    }

    public function scopeForMyTasks(Builder $query, int $userId, string $viewMode): Builder
    {
        return $query->where(function (Builder $q) use ($userId, $viewMode) {
            if ($viewMode === 'applied') {
                $q->whereHas('offers', fn($sub) => $sub->where('user_id', $userId));
            } elseif ($viewMode === 'direct') {
                $q->where('is_direct', true)
                  ->where(fn($sub) => $sub->where('employer_id', $userId)->orWhere('employee_id', $userId));
            } else {
                $q->where('employer_id', $userId)->where('is_direct', false);
            }
        });
    }

    public function scopeByStatusFilter(Builder $query, string $status): Builder
    {
        if ($status === 'pending' || $status === 'assigned') {
            return $query->where('status', TaskStatus::Assigned);
        } elseif ($status === 'completed') {
            return $query->where('status', TaskStatus::Completed);
        }

        return $query->where('status', TaskStatus::Open);
    }

    public function scopeByTaskType(Builder $query, ?string $type): Builder
    {
        if (!$type || $type === 'all') {
            return $query;
        }

        // Map 'remote' filter value to 'online' task_type
        $dbType = ($type === 'remote') ? 'online' : 'in_person';

        return $query->where('task_type', $dbType);
    }

    public function scopeByLocation(Builder $query, ?string $location): Builder
    {
        if (!$location) {
            return $query;
        }

        return $query->where('location', 'like', "%{$location}%");
    }

    // ── Domain Helpers ───────────────────────────────────────

    public function isOwner(int $userId): bool
    {
        return $this->employer_id === $userId;
    }

    public function isOpen(): bool
    {
        return $this->status === TaskStatus::Open;
    }

    public function isAssigned(): bool
    {
        return $this->status === TaskStatus::Assigned;
    }

    public function isCompleted(): bool
    {
        return $this->status === TaskStatus::Completed;
    }

    public function scopeApplySorting(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'lowest_price' => $query->orderBy('price', 'asc'),
            'highest_price' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }

    public function hasEmployee(): bool
    {
        return $this->employee_id !== null;
    }
}