<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatafeedItem extends Model
{
    /** @use HasFactory<\Database\Factories\DatafeedItemFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'datafeeds_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'aw_deep_link',
        'product_name',
        'aw_product_id',
        'merchant_product_id',
        'merchant_image_url',
        'description',
        'merchant_category',
        'search_price',
        'merchant_name',
        'merchant_id',
        'category_name',
        'category_id',
        'aw_image_url',
        'currency',
        'store_price',
        'delivery_cost',
        'merchant_deep_link',
        'language',
        'last_updated',
        'display_price',
        'data_feed_id',
        'product_model',
        'model_number',
        'dimensions',
        'brand_name',
        'brand_id',
        'colour',
        'product_short_description',
        'specifications',
        'condition',
        'keywords',
        'promotional_text',
        'product_type',
        'commission_group',
        'merchant_product_category_path',
        'merchant_product_second_category',
        'merchant_product_third_category',
        'rrp_price',
        'saving',
        'savings_percent',
        'base_price',
        'base_price_amount',
        'base_price_text',
        'product_price_old',
        'delivery_restrictions',
        'delivery_weight',
        'warranty',
        'terms_of_contract',
        'delivery_time',
        'in_stock',
        'stock_quantity',
        'valid_from',
        'valid_to',
        'is_for_sale',
        'web_offer',
        'pre_order',
        'stock_status',
        'size_stock_status',
        'size_stock_amount',
        'merchant_thumb_url',
        'large_image',
        'alternate_image',
        'aw_thumb_url',
        'alternate_image_two',
        'alternate_image_three',
        'alternate_image_four',
        'reviews',
        'average_rating',
        'rating',
        'number_available',
        'custom_1',
        'custom_2',
        'custom_3',
        'custom_4',
        'custom_5',
        'custom_6',
        'custom_7',
        'custom_8',
        'custom_9',
        'ean',
        'isbn',
        'upc',
        'mpn',
        'parent_product_id',
        'product_GTIN',
        'basket_link',
        'fashion_suitable_for',
        'fashion_category',
        'fashion_size',
        'fashion_material',
        'fashion_pattern',
        'fashion_swatch',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // All fields are text, no special casting needed
    ];
}
