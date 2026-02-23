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