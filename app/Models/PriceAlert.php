<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceAlert extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'target_price',
        'is_active',
        'last_notified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_price' => 'decimal:2',
        'is_active' => 'boolean',
        'last_notified_at' => 'datetime',
    ];

    /**
     * Get the user that created the alert.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product for this alert.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if price alert should trigger for given price.
     */
    public function shouldTrigger(float $currentPrice): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->target_price === null) {
            return true; // Alert for any price change
        }

        return $currentPrice <= $this->target_price;
    }
}
