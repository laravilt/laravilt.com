<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
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
        'category_id',
        'name',
        'slug',
        'description',
        'content',
        'price',
        'compare_price',
        'stock',
        'sku',
        'image',
        'gallery',
        'status',
        'is_featured',
        'is_downloadable',
        'team_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'stock' => 'integer',
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_downloadable' => 'boolean',
    ];

    /**
     * Scope to filter by current user (for demo isolation).
     */
    public function scopeForUser(Builder $query, ?int $userId = null): Builder
    {
        return $query->where('user_id', $userId ?? auth()->id());
    }

    /**
     * Scope to filter published products.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to filter featured products.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if product is on sale.
     */
    public function getOnSaleAttribute(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    /**
     * Get discount percentage.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->on_sale) {
            return null;
        }
        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }
}
