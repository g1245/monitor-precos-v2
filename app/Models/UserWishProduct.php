<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWishProduct extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_wish_products';

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
     * Get the user that wished for the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wished product.
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

    /**
     * Check if this is just a wish (no price alert).
     */
    public function isWishOnly(): bool
    {
        return $this->target_price === null;
    }

    /**
     * Check if this has a price alert configured.
     */
    public function hasPriceAlert(): bool
    {
        return $this->target_price !== null;
    }
}
