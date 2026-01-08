<?php

namespace App\Services;

use App\Dto\ProductDto;
use App\Models\Product;

class ProductService
{
    /**
     * Create a new product using the provided DTO.
     */
    public static function create(ProductDto $dto): Product
    {
        return Product::create($dto->toArray());
    }

    /**
     * Create or update a product using the provided DTO.
     * Uses sku as unique identifier.
     */
    public static function createOrUpdate(ProductDto $dto): Product
    {
        return Product::updateOrCreate(
            ['sku' => $dto->sku],
            $dto->toArray()
        );
    }
}