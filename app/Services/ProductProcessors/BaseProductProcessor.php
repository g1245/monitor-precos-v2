<?php

namespace App\Services\ProductProcessors;

use App\Models\Product;

abstract class BaseProductProcessor implements ProductProcessorInterface
{
    /**
     * The internal store name that this processor handles.
     */
    protected string $storeInternalName;

    /**
     * Process a product according to store-specific rules.
     *
     * @param Product $product The product to process
     * @return void
     */
    abstract public function process(Product $product): void;

    /**
     * Check if this processor can handle the given store.
     *
     * @param int $storeId The store ID to check
     * @return bool
     */
    public function canHandle(int $storeId): bool
    {
        $store = \App\Models\Store::find($storeId);

        if (!$store) {
            return false;
        }

        return $store->internal_name === $this->storeInternalName;
    }
}
