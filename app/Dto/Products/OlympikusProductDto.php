<?php

namespace App\Dto\Products;

use App\Dto\ProductDto;

/**
 * DTO for Olympikus store products.
 *
 * Olympikus price mapping:
 * - `price_min` → price (lowest selling price across variants)
 * - `priceRegular` is always null (historical price tracked via `highest_recorded_price`)
 * - `externalLink` is mapped from the first variant's `merchant_deep_link`
 * - `deepLink` is mapped from the root-level `aw_deep_link`
 */
class OlympikusProductDto extends ProductDto
{
    /**
     * {@inheritdoc}
     *
     * Overrides price mapping to use `price_min` as the selling price.
     * `priceRegular` is always null; historical price is tracked via `highest_recorded_price`.
     * `externalLink` is resolved from the first available variant's `merchant_deep_link`.
     */
    public static function fromApiData(int $storeId, array $product): static
    {
        return new static(
            storeId: $storeId,
            name: $product['product_name'],
            description: $product['description'] ?? null,
            price: $product['price']['price_min'] ?? null,
            priceRegular: null,
            sku: $product['merchant_product_id'],
            brand: $product['brand_name'] ?? null,
            imageUrl: $product['merchant_image_url'],
            deepLink: $product['aw_deep_link'] ?? null,
            externalLink: $product['aw_deep_link'] ?? null,
            merchantProductId: $product['merchant_product_id'] ?? null,
        );
    }

    /**
     * {@inheritdoc}
     *
     * Olympikus requires `price_min`.
     */
    public static function hasValidPrices(array $priceData): bool
    {
        return !empty($priceData['price_min']);
    }
}
