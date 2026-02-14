<?php

namespace App\Models;

use App\Models\UserWishProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceAlertLog extends Model
{
    /** @use HasFactory<\Database\Factories\PriceAlertLogFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'user_wish_product_id',
        'price_at_notification',
        'target_price',
        'notified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_at_notification' => 'decimal:2',
        'target_price' => 'decimal:2',
        'notified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that owns the log.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user wish product that owns the log.
     */
    public function userWishProduct(): BelongsTo
    {
        return $this->belongsTo(UserWishProduct::class);
    }
}