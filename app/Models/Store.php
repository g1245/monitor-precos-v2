<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Store Model
 *
 * Represents a virtual store that sells products.
 *
 * @property int $id
 * @property string $name
 * @property string|null $logo
 * @property string|null $region
 * @property bool $has_public_catalog
 * @property string $full_url
 * @property array|null $metadata
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'logo',
        'region',
        'has_public_catalog',
        'full_url',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'has_public_catalog' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the products available in this store.
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_store')
            ->withPivot('price', 'product_url')
            ->withTimestamps();
    }

    /**
     * Get the store feeds for this store.
     *
     * @return HasMany
     */
    public function storeFeeds(): HasMany
    {
        return $this->hasMany(StoreFeed::class);
    }

    /**
     * Get the slug version of the store name.
     *
     * @return string
     */
    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->name);
    }

    /**
     * Get the public URL for the store page.
     *
     * @return string
     */
    public function getPublicUrlAttribute(): string
    {
        return route('store.show', ['id' => $this->id, 'slug' => $this->slug]);
    }
}
