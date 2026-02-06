<?php

namespace App\Services;

use App\Dto\ProductAttributeDto;
use App\Models\ProductAttribute;

class ProductAttributeService
{
    /**
     * Sync product attributes based on DTO.
     * Updates existing or creates new attributes for each field.
     */
    public static function sync(ProductAttributeDto $dto): void
    {
        $attributes = $dto->toAttributesArray();

        foreach ($attributes as $attribute) {
            ProductAttribute::updateOrCreate(
                [
                    'product_id' => $dto->productId,
                    'key' => $attribute['key'],
                ],
                [
                    'description' => $attribute['description'],
                ]
            );
        }
    }

    /**
     * Create or update a specific product attribute.
     */
    public static function createOrUpdate(int $productId, string $key, string $description): ProductAttribute
    {
        return ProductAttribute::updateOrCreate(
            [
                'product_id' => $productId,
                'key' => $key,
            ],
            [
                'description' => $description,
            ]
        );
    }

    /**
     * Delete all attributes for a product.
     */
    public static function deleteAll(int $productId): void
    {
        ProductAttribute::where('product_id', $productId)->delete();
    }

    /**
     * Get all attributes for a product as key-value pairs.
     */
    public static function getAttributesForProduct(int $productId): array
    {
        $attributes = ProductAttribute::where('product_id', $productId)->get();
        
        $result = [];
        foreach ($attributes as $attribute) {
            $result[$attribute->key] = $attribute->description;
        }
        
        return $result;
    }
}
