<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the teams that have access to this product.
     */
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'avatar',
        'status',
        'preferences',
        'notes',
        'total_spent',
        'orders_count',
        'last_order_at',
        'team_id'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'preferences' => 'array',
        'total_spent' => 'decimal:2',
        'orders_count' => 'integer',
        'last_order_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    /**
     * Get full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope to filter by current user (for demo isolation).
     */
    public function scopeForUser(Builder $query, ?int $userId = null): Builder
    {
        return $query->where('user_id', $userId ?? auth()->id());
    }

    /**
     * Scope to filter active customers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Update statistics after an order.
     */
    public function updateOrderStats(): void
    {
        $this->update([
            'orders_count' => $this->orders()->count(),
            'total_spent' => $this->orders()->where('payment_status', 'paid')->sum('total'),
            'last_order_at' => $this->orders()->latest()->first()?->created_at,
        ]);
    }
}
