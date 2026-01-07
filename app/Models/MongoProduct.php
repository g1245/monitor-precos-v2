<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * MongoProduct Model
 *
 * Represents products stored in MongoDB.
 * Used for browsing and searching products imported from store feeds.
 *
 * @property string $merchant_product_id Unique identifier from merchant
 * @property string $product_name Product name
 * @property string $aw_product_id Awin product identifier
 * @property string $merchant_image_url Product image URL
 * @property string $description Product description
 * @property string $merchant_category Product category
 * @property array $prices Price history array
 * @property float $search_price Current search price
 * @property float $rrp_price Recommended retail price
 * @property string $currency Currency code
 * @property string $merchant_deep_link Direct link to product
 * @property string $merchant_name Merchant/store name
 * @property int $merchant_id Merchant identifier
 * @property string $category_name Category name
 * @property int $category_id Category identifier
 * @property string $brand_name Brand name
 * @property int $brand_id Brand identifier
 * @property string $colour Product color
 * @property string $product_short_description Short description
 * @property string $specifications Product specifications
 * @property string $condition Product condition
 * @property string $product_model Product model
 * @property string $model_number Model number
 * @property string $dimensions Product dimensions
 * @property string $keywords Keywords for search
 * @property string $promotional_text Promotional information
 * @property string $product_type Product type
 * @property int $commission_group_id Commission group
 * @property string $merchant_product_category_path Category path
 * @property string $merchant_product_second_category Second category
 * @property string $merchant_product_third_category Third category
 * @property string $language Language code
 * @property string $last_updated Last update timestamp
 * @property string $aw_deep_link Awin deep link
 * @property string $aw_image_url Awin image URL
 * @property string $alternate_image Alternative image URL
 * @property string $alternate_image_two Second alternative image
 * @property string $alternate_image_three Third alternative image
 * @property string $alternate_image_four Fourth alternative image
 * @property string $merchant_thumb_url Thumbnail URL
 * @property int $large_image Large image flag
 * @property string $delivery_cost Delivery cost
 * @property string $merchant_warranty Warranty information
 * @property string $delivery_restrictions Delivery restrictions
 * @property string $delivery_weight Delivery weight
 * @property string $stock_status Stock status
 * @property int $stock_quantity Stock quantity
 * @property string $valid_from Valid from date
 * @property string $valid_to Valid to date
 * @property string $is_for_sale Sale flag
 * @property string $web_offer Web offer flag
 * @property string $pre_order Pre-order flag
 * @property string $in_stock In stock flag
 * @property string $warranty Warranty details
 * @property string $terms_of_contract Contract terms
 * @property string $delivery_time Delivery time
 * @property string $custom_1 Custom field 1
 * @property string $custom_2 Custom field 2
 * @property string $custom_3 Custom field 3
 * @property string $custom_4 Custom field 4
 * @property string $custom_5 Custom field 5
 * @property string $custom_6 Custom field 6
 * @property string $custom_7 Custom field 7
 * @property string $custom_8 Custom field 8
 * @property string $custom_9 Custom field 9
 * @property \MongoDB\BSON\UTCDateTime $created_at
 */
class MongoProduct extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'merchant_product_id',
        'product_name',
        'aw_product_id',
        'merchant_image_url',
        'description',
        'merchant_category',
        'prices',
        'search_price',
        'rrp_price',
        'currency',
        'merchant_deep_link',
        'merchant_name',
        'merchant_id',
        'category_name',
        'category_id',
        'brand_name',
        'brand_id',
        'colour',
        'product_short_description',
        'specifications',
        'condition',
        'product_model',
        'model_number',
        'dimensions',
        'keywords',
        'promotional_text',
        'product_type',
        'commission_group_id',
        'merchant_product_category_path',
        'merchant_product_second_category',
        'merchant_product_third_category',
        'language',
        'last_updated',
        'aw_deep_link',
        'aw_image_url',
        'alternate_image',
        'alternate_image_two',
        'alternate_image_three',
        'alternate_image_four',
        'merchant_thumb_url',
        'large_image',
        'delivery_cost',
        'merchant_warranty',
        'delivery_restrictions',
        'delivery_weight',
        'stock_status',
        'stock_quantity',
        'valid_from',
        'valid_to',
        'is_for_sale',
        'web_offer',
        'pre_order',
        'in_stock',
        'warranty',
        'terms_of_contract',
        'delivery_time',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prices' => 'array',
        'search_price' => 'float',
        'rrp_price' => 'float',
        'stock_quantity' => 'integer',
        'merchant_id' => 'integer',
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'commission_group_id' => 'integer',
        'large_image' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Get the latest price information.
     *
     * @return array|null
     */
    public function getLatestPrice(): ?array
    {
        if (empty($this->prices) || !is_array($this->prices)) {
            return null;
        }

        return end($this->prices);
    }

    /**
     * Get formatted search price.
     *
     * @return string
     */
    public function getFormattedSearchPrice(): string
    {
        $currency = $this->currency ?? 'BRL';
        $price = $this->search_price ?? 0;

        return $currency . ' ' . number_format($price, 2, ',', '.');
    }

    /**
     * Get formatted RRP price.
     *
     * @return string
     */
    public function getFormattedRrpPrice(): string
    {
        $currency = $this->currency ?? 'BRL';
        $price = $this->rrp_price ?? 0;

        return $currency . ' ' . number_format($price, 2, ',', '.');
    }

    /**
     * Get price discount percentage.
     *
     * @return float|null
     */
    public function getDiscountPercentage(): ?float
    {
        if (empty($this->rrp_price) || empty($this->search_price)) {
            return null;
        }

        if ($this->rrp_price <= $this->search_price) {
            return null;
        }

        $discount = (($this->rrp_price - $this->search_price) / $this->rrp_price) * 100;

        return round($discount, 2);
    }

    /**
     * Check if product has discount.
     *
     * @return bool
     */
    public function hasDiscount(): bool
    {
        return $this->getDiscountPercentage() !== null;
    }

    /**
     * Get product thumbnail URL.
     *
     * @return string|null
     */
    public function getThumbnailUrl(): ?string
    {
        return $this->merchant_thumb_url 
            ?? $this->aw_image_url 
            ?? $this->merchant_image_url;
    }

    /**
     * Scope to filter by merchant name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $merchantName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByMerchant($query, string $merchantName)
    {
        return $query->where('merchant_name', 'like', '%' . $merchantName . '%');
    }

    /**
     * Scope to filter by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('merchant_category', 'like', '%' . $category . '%');
    }

    /**
     * Scope to filter by in stock products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStock($query)
    {
        return $query->where('in_stock', 'yes');
    }

    /**
     * Scope to search products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('product_name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('brand_name', 'like', '%' . $search . '%')
                ->orWhere('keywords', 'like', '%' . $search . '%');
        });
    }
}
