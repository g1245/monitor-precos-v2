<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get saved products for this user.
     */
    public function savedProducts(): HasMany
    {
        return $this->hasMany(SavedProduct::class);
    }

    /**
     * Get products saved by this user through the pivot table.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'saved_products')
            ->withTimestamps();
    }

    /**
     * Get price alerts for this user.
     */
    public function priceAlerts(): HasMany
    {
        return $this->hasMany(PriceAlert::class);
    }

    /**
     * Get browsing history for this user.
     */
    public function browsingHistory(): HasMany
    {
        return $this->hasMany(UserBrowsingHistory::class);
    }

    /**
     * Check if user has saved a specific product.
     */
    public function hasSavedProduct(int $productId): bool
    {
        return $this->savedProducts()->where('product_id', $productId)->exists();
    }

    /**
     * Check if user has a price alert for a specific product.
     */
    public function hasPriceAlert(int $productId): bool
    {
        return $this->priceAlerts()->where('product_id', $productId)->where('is_active', true)->exists();
    }
}
