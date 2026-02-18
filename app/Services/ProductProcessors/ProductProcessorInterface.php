<?php

namespace App\Services\ProductProcessors;

use App\Models\Product;

interface ProductProcessorInterface
{
    /**
     * Process a product according to store-specific rules.
     *
     * @param Product $product The product to process
     * @return void
     */
    public function process(Product $product): void;

    /**
     * Check if this processor can handle the given store.
     *
     * @param int $storeId The store ID to check
     * @return bool
     */
    public function canHandle(int $storeId): bool;
}
