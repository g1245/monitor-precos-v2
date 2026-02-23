<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'store_id',
        'name',
        'description',
        'price',
        'price_regular',
        'sku',
        'merchant_product_id',
        'brand',
        'image_url',
        'is_active',
        'is_parent',
        'deep_link',
        'external_link',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'store_id' => 'integer',
        'price' => 'decimal:2',
        'price_regular' => 'decimal:2',
        'is_active' => 'boolean',
        'is_parent' => 'integer',
        'deep_link' => 'string',
        'external_link' => 'string',
        'discount_percentage' => 'integer',
    ];

    /**
     * Get the public URL for the store page.
     *
     * @return string
     */
    public function getPermalinkAttribute(): string
    {
        return Str::slug($this->name);
    }

    /**
     * Get all departments that this product belongs to.
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'departments_products');
    }

    /**
     * Get the primary store for this product.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
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
     * Scope to search products by name, description, brand or exact SKU match.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('sku', '=', $search)
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('brand', 'LIKE', "%{$search}%");
        });
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
     * Ensures only one price per specified date by updating if already exists.
     * If $date is null, uses today's date.
     */
    public function addPriceHistory(float $price, ?string $date = null): ProductPriceHistory
    {
        $targetDate = $date ?? now()->toDateString();

        $existing = $this->priceHistories()->whereDate('created_at', $targetDate)->first();

        if ($existing) {
            $existing->update(['price' => $price]);
            return $existing;
        }

        return $this->priceHistories()->create([
            'price' => $price,
            'created_at' => $targetDate . ' ' . now()->toTimeString(), // Preserve time if needed, but date is key
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

    /**
     * Get users who wished for this product.
     */
    public function wishedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_wish_products')
            ->withTimestamps();
    }

    /**
     * Get user wish products for this product.
     */
    public function userWishProducts(): HasMany
    {
        return $this->hasMany(UserWishProduct::class);
    }

    /**
     * Get active price alerts for this product (wishes with target price).
     */
    public function activePriceAlerts(): HasMany
    {
        return $this->hasMany(UserWishProduct::class)
            ->whereNotNull('target_price')
            ->where('is_active', true);
    }

    /**
     * Check if this product is a parent product.
     */
    public function isParentProduct(): bool
    {
        return $this->is_parent === 0;
    }

    /**
     * Check if this product is a child product.
     */
    public function isChildProduct(): bool
    {
        return $this->is_parent !== null && $this->is_parent > 0;
    }

    /**
     * Get the parent product if this is a child product.
     */
    public function parentProduct(): ?Product
    {
        if (!$this->isChildProduct()) {
            return null;
        }

        return Product::find($this->is_parent);
    }

    /**
     * Get child products if this is a parent product.
     */
    public function childProducts()
    {
        if (!$this->isParentProduct()) {
            return collect([]);
        }

        return Product::where('is_parent', $this->id)->get();
    }
}
