<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'permalink',
        'description',
        'price',
        'regular_price',
        'sku',
        'brand',
        'image_url',
        'is_active',
        'vector_search',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'regular_price' => 'decimal:2',
        'is_active' => 'boolean',
        'vector_search' => 'array',
    ];

    /**
     * The model's boot method.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->permalink)) {
                $model->permalink = uniqid();
            }
        });
    }

    /**
     * Get all departments that this product belongs to.
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'departments_products');
    }

    /**
     * Get all attributes for this product.
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Get price history for this product.
     */
    public function priceHistories(): HasMany
    {
        return $this->hasMany(ProductPriceHistory::class);
    }

    /**
     * Get all stores that sell this product.
     */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class)
            ->withPivot('price', 'product_url')
            ->withTimestamps();
    }

    /**
     * Scope to get only active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search products by name or description.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('sku', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope to find by permalink.
     */
    public function scopeByPermalink($query, string $permalink)
    {
        return $query->where('permalink', $permalink);
    }

    /**
     * Scope to filter products by price range.
     */
    public function scopePriceBetween($query, float $min, float $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Check if product is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Add price to history.
     */
    public function addPriceHistory(float $price): ProductPriceHistory
    {
        return $this->priceHistories()->create([
            'price' => $price,
        ]);
    }

    /**
     * Get latest price from history.
     */
    public function getLatestHistoricalPrice(): ?float
    {
        $latestHistory = $this->priceHistories()->latest('created_at')->first();
        
        return $latestHistory?->price;
    }

    /**
     * Check if current price should be recorded in history.
     * Only record if price has changed from last recorded price.
     */
    public function shouldRecordPriceHistory(): bool
    {
        $latestPrice = $this->getLatestHistoricalPrice();
        
        return $latestPrice === null || $latestPrice !== $this->price;
    }
}