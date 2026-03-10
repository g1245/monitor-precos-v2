<?php

namespace App\Services\ProductProcessors;

use App\Models\Product;

class DefaultProductProcessor extends BaseProductProcessor
{
    /**
     * The internal store name that this processor handles.
     */
    protected string $storeInternalName = '';

    /**
     * The default processor handles any store that has no dedicated processor.
     *
     * @param int $storeId The store ID to check
     * @return bool Always false — this processor is used only as an explicit fallback.
     */
    public function canHandle(int $storeId): bool
    {
        return false;
    }

    /**
     * Apply generic processing rules for stores without a dedicated processor.
     *
     * @param Product $product The product to process
     * @return void
     */
    public function process(Product $product): void
    {
        $this->logger()->info('Processing product with default fallback processor', [
            'product_id' => $product->id,
            'store_id'   => $product->store_id,
            'sku'        => $product->sku,
        ]);

        $product->is_parent = 0;
        $product->save();
    }
}
