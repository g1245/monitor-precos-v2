<?php

namespace App\Services\ProductProcessors;

use App\Models\Product;

class OlympikusProductProcessor extends BaseProductProcessor
{
    /**
     * The internal store name that this processor handles.
     */
    protected string $storeInternalName = 'olympikus';

    /**
     * Process a product according to Olympikus store-specific rules.
     *
     * @param Product $product The product to process
     * @return void
     */
    public function process(Product $product): void
    {
        $this->logger()->info('[OlympikusProductProcessor] Processing product for Olympikus', [
            'product_id' => $product->id,
            'sku' => $product->sku,
        ]);

        $product->is_parent = 0; // Default to not a parent product
        $product->save();
    }
}
