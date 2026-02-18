<?php

namespace App\Services\ProductProcessors;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CentauroProductProcessor extends BaseProductProcessor
{
    /**
     * The internal store name that this processor handles.
     */
    protected string $storeInternalName = 'centauro';

    /**
     * Process a product according to Centauro store-specific rules.
     *
     * @param Product $product The product to process
     * @return void
     */
    public function process(Product $product): void
    {
        Log::info('Processing product for Centauro', [
            'product_id' => $product->id,
            'sku' => $product->sku,
        ]);

        // TODO: Implement Centauro-specific product processing logic
        // This is where business rules for Centauro products will be applied
        // For example:
        // - Identify configurable products (e.g., shoes with multiple sizes)
        // - Group products by SKU patterns
        // - Set is_parent = 0 for parent products
        // - Set is_parent = {parent_id} for child products
    }
}
