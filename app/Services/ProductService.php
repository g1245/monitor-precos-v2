<?php

namespace App\Services;

use App\Dto\ProductDto;
use App\Models\Product;

class ProductService
{
    /**
     * Create a new product using the provided DTO.
     */
    public function create(ProductDto $dto): Product
    {
        return Product::create($dto->toArray());
    }
}