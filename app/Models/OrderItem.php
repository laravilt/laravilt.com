<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'total_price',
        'discount',
        'options',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'options' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Auto-calculate total price
            if (empty($item->total_price)) {
                $item->total_price = ($item->unit_price * $item->quantity) - ($item->discount ?? 0);
            }

            // Snapshot product info
            if ($item->product && empty($item->product_name)) {
                $item->product_name = $item->product->name;
                $item->product_sku = $item->product->sku;
                $item->unit_price = $item->unit_price ?? $item->product->price;
            }
        });

        static::updating(function ($item) {
            // Recalculate total price
            $item->total_price = ($item->unit_price * $item->quantity) - ($item->discount ?? 0);
        });

        static::saved(function ($item) {
            // Update order totals
            $item->order?->calculateTotals();
            $item->order?->save();
        });

        static::deleted(function ($item) {
            // Update order totals
            $item->order?->calculateTotals();
            $item->order?->save();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
