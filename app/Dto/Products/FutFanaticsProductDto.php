<?php

namespace App\Dto\Products;

use App\Dto\ProductDto;

/**
 * DTO for FutFanatics store products.
 *
 * FutFanatics uses `search_price` as the selling price and `product_price_old` as the regular price.
 * Prices are mapped as:
 * - `search_price` → price (actual selling price)
 * - `product_price_old` → priceRegular (original price)
 */
class FutFanaticsProductDto extends ProductDto
{
    /**
     * {@inheritdoc}
     *
     * Overrides price mapping to use `search_price` as the selling price and
     * `product_price_old` as the regular price.
     */
    public static function fromApiData(int $storeId, array $product): static
    {
        $priceData = $product['price'] ?? [];

        return new static(
            storeId: $storeId,
            name: $product['product_name'],
            description: $product['description'] ?? null,
            price: isset($priceData['search_price']) ? (float) $priceData['search_price'] : (isset($priceData['display_price']) ? (float) $priceData['display_price'] : null),
            priceRegular: isset($priceData['product_price_old']) ? (float) $priceData['product_price_old'] : null,
            sku: $product['merchant_product_id'],
            brand: $product['brand_name'] ?? null,
            imageUrl: $product['merchant_image_url'],
            deepLink: $product['aw_deep_link'] ?? null,
            externalLink: $product['merchant_deep_link'] ?? null,
            merchantProductId: $product['merchant_product_id'] ?? null,
        );
    }

    /**
     * {@inheritdoc}
     *
     * FutFanatics requires either `search_price` or `display_price` to be present.
     */
    public static function hasValidPrices(array $priceData): bool
    {
        return !empty($priceData['search_price']) || !empty($priceData['display_price']);
    }
}