<?php

namespace App\Services\ProductProcessors;

use App\Models\Product;

class AdidasProductProcessor extends BaseProductProcessor
{
    /**
     * The internal store name that this processor handles.
     */
    protected string $storeInternalName = 'adidas';

    /**
     * Process a product according to Adidas store-specific rules.
     *
     * Adidas SKUs follow the pattern: {ProductCode}-{Variant}, e.g. JI4760-0006_44-45.
     * The first segment before the first hyphen is the product code used to group variants.
     *
     * @param Product $product The product to process
     * @return void
     */
    public function process(Product $product): void
    {
        $this->logger()->info('[AdidasProductProcessor] Processing product for Adidas', [
            'product_id' => $product->id,
            'sku' => $product->sku,
        ]);

        $productCode = explode('-', $product->sku)[0] ?? null;

        if (!$productCode) {
            $product->is_parent = 0;
            $product->save();

            $this->logger()->info('[AdidasProductProcessor] Could not extract product code from SKU, set as standalone parent', [
                'product_id' => $product->id,
                'sku' => $product->sku,
            ]);

            return;
        }

        // Find all products from the same store whose SKU starts with the same product code
        $productsInGroup = Product::where('store_id', $product->store_id)
            ->where('sku', 'like', $productCode . '-%')
            ->get();

        // The product with the smallest ID is the parent
        $parentId = $productsInGroup->min('id');

        foreach ($productsInGroup as $groupProduct) {
            if ($groupProduct->id == $parentId) {
                $groupProduct->is_parent = 0;

                $this->logger()->info('[AdidasProductProcessor] Product is the parent of the group', [
                    'product_id' => $groupProduct->id,
                    'product_code' => $productCode,
                ]);
            } else {
                $groupProduct->is_parent = $parentId;

                $this->logger()->info('[AdidasProductProcessor] Product is a child of the group', [
                    'product_id' => $groupProduct->id,
                    'parent_id' => $parentId,
                    'product_code' => $productCode,
                ]);
            }

            $groupProduct->save();
        }

        $this->logger()->info('[AdidasProductProcessor] Finished processing product group for Adidas', [
            'input_product_id' => $product->id,
            'product_code' => $productCode,
            'group_size' => $productsInGroup->count(),
            'parent_id' => $parentId,
        ]);
    }
}
