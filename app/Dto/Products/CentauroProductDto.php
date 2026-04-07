<?php

namespace App\Dto\Products;

use App\Dto\ProductDto;

/**
 * DTO for Centauro store products.
 *
 * Centauro price mapping:
 * - `search_price` → price (actual selling price, fallback to `display_price` or `base_price`)
 * - `priceRegular` is always null (historical price tracked via `highest_recorded_price`)
 * - `externalLink` is mapped from `custom_1` (canonical product URL)
 */
class CentauroProductDto extends ProductDto
{
    /**
     * {@inheritdoc}
     *
     * Overrides price mapping to use `search_price` as the selling price.
     * `priceRegular` is always null; historical price is tracked via `highest_recorded_price`.
     * `externalLink` is sourced from `custom_1`, which contains the canonical product URL.
     */
    public static function fromApiData(int $storeId, array $product): static
    {
        $priceData = $product['price'] ?? [];

        $price = (float) (
            $priceData['base_price']
            ?? $priceData['search_price']
            ?? $priceData['display_price']
            ?? 0
        );

        return new static(
            storeId: $storeId,
            name: $product['product_name'],
            description: $product['description'] ?? null,
            price: $price,
            priceRegular: null,
            sku: $product['merchant_product_id'],
            brand: $product['brand_name'] ?? null,
            imageUrl: $product['merchant_image_url'],
            deepLink: $product['aw_deep_link'] ?? null,
            externalLink: $product['custom_1'] ?? $product['merchant_deep_link'] ?? null,
            merchantProductId: $product['merchant_product_id'] ?? null,
        );
    }

    /**
     * {@inheritdoc}
     *
     * Centauro requires at least one of `search_price`, `display_price`, or `base_price`.
     */
    public static function hasValidPrices(array $priceData): bool
    {
        return !empty($priceData['search_price'])
            || !empty($priceData['display_price'])
            || !empty($priceData['base_price']);
    }
}
