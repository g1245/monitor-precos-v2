<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceAlert extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'email',
        'phone',
        'target_price',
        'is_notified',
        'notified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'product_id' => 'integer',
        'target_price' => 'decimal:2',
        'is_notified' => 'boolean',
        'notified_at' => 'datetime',
    ];

    /**
     * Get the product that owns the price alert.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get only alerts that haven't been notified.
     */
    public function scopeNotNotified($query)
    {
        return $query->where('is_notified', false);
    }
}
