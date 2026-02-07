<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Store Model
 *
 * Represents a virtual store that sells products.
 *
 * @property int $id
 * @property string $name
 * @property string|null $logo
 * @property bool $has_public
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
        'internal_name',
        'logo',
        'metadata',
        'has_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'has_public' => 'boolean',
    ];

    /**
     * Get the public URL for the store page.
     *
     * @return string
     */
    public function getPublicUrlAttribute(): string
    {
        return route('store.show', ['id' => $this->id, 'slug' => $this->getSlug()]);
    }

    /**
     * Get the slug for the store based on its name.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return Str::slug($this->name);
    }


    /**
     * Get the products associated with the store.
     *
     * @return HasMany
     */ 
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
