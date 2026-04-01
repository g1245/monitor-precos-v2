<?php

namespace App\Dto\Products;

use App\Dto\ProductDto;

/**
 * DTO for Kabum store products.
 *
 * Kabum does not use `base_price`. Prices are mapped as:
 * - `display_price` → price / priceRegular (original displayed price)
 */
class KabumProductDto extends ProductDto
{
    /**
     * {@inheritdoc}
     *
     * Overrides price mapping to use `display_price` as the regular price
     * instead of the default `base_price` / `product_price_old` fields.
     */
    public static function fromApiData(int $storeId, array $product): static
    {
        $priceData = $product['price'] ?? [];

        return new static(
            storeId: $storeId,
            name: $product['product_name'],
            description: $product['description'] ?? null,
            price: (float) $priceData['display_price'],
            priceRegular: (float) $priceData['display_price'],
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
     * Kabum uses `display_price` instead of `base_price` as the fallback price field.
     */
    public static function hasValidPrices(array $priceData): bool
    {
        return !empty($priceData['search_price']) || !empty($priceData['display_price']);
    }
}
