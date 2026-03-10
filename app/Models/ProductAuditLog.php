<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductAuditLog Model
 *
 * Stores a snapshot of all product fields each time a product is created or
 * updated. The log is unique per store/product pair and is automatically pruned
 * after two days via the Prunable trait.
 *
 * @property int $id
 * @property int $store_id
 * @property int $product_id
 * @property string $event
 * @property array $product_snapshot
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon $created_at
 */
class ProductAuditLog extends Model
{
    use Prunable;

    /**
     * Disable automatic timestamp management (table only has created_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_id',
        'product_id',
        'event',
        'product_snapshot',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'product_snapshot' => 'array',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Define the prunable query: remove records whose expiry has passed.
     */
    public function prunable(): \Illuminate\Database\Eloquent\Builder
    {
        return static::where('expires_at', '<=', now());
    }

    /**
     * Get the store that owns the log.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the product that owns the log.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
