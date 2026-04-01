<?php

namespace App\Dto;

class ProductDto
{
    public function __construct(
        public int $storeId,
        public string $name,
        public ?string $description = null,
        public ?float $price = null,
        public ?float $priceRegular = null,
        public ?string $sku = null,
        public ?string $brand = null,
        public ?string $imageUrl = null,
        public ?string $deepLink = null,
        public ?string $externalLink = null,
        public ?string $merchantProductId = null,
    ) { }

    /**
     * Create a DTO instance from raw API product data.
     * Store-specific DTOs should override this method to handle different field structures.
     *
     * @param int   $storeId
     * @param array $product Raw product data from the API
     * @return static
     */
    public static function fromApiData(int $storeId, array $product): static
    {
        $priceData = $product['price'] ?? [];

        return new static(
            storeId: $storeId,
            name: $product['product_name'],
            description: $product['description'] ?? null,
            price: isset($priceData['search_price']) ? (float) $priceData['search_price'] : null,
            priceRegular: isset($priceData['product_price_old'])
                ? (float) $priceData['product_price_old']
                : (isset($priceData['base_price']) ? (float) $priceData['base_price'] : null),
            sku: $product['merchant_product_id'],
            brand: $product['brand_name'] ?? null,
            imageUrl: $product['merchant_image_url'],
            deepLink: $product['aw_deep_link'] ?? null,
            externalLink: $product['merchant_deep_link'] ?? null,
            merchantProductId: $product['merchant_product_id'] ?? null,
        );
    }

    /**
     * Validate that the price data contains the required fields for this DTO type.
     * Store-specific DTOs should override this to match their own price field requirements.
     *
     * @param array $priceData The price sub-array from the raw API product
     * @return bool
     */
    public static function hasValidPrices(array $priceData): bool
    {
        return !empty($priceData['search_price']) || !empty($priceData['base_price']);
    }

    /**
     * Convert DTO to array for mass assignment.
     */
    public function toArray(): array
    {
        return [
            'store_id' => $this->storeId,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'price_regular' => $this->priceRegular,
            'sku' => $this->sku,
            'brand' => $this->brand,
            'image_url' => $this->imageUrl,
            'deep_link' => $this->deepLink,
            'external_link' => $this->externalLink,
            'merchant_product_id' => $this->merchantProductId,
        ];
    }
}