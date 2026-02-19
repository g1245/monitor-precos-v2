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
        Log::info('[CentauroProductProcessor] Processing product for Centauro', [
            'product_id' => $product->id,
            'sku' => $product->sku,
        ]);

        // Get the custom_4 attribute for this product
        $custom4Attribute = $product->attributes()->where('key', 'custom_4')->first();

        if (!$custom4Attribute) {
            // If no custom_4, treat as standalone product (parent)
            $product->is_parent = 0;
            $product->save();

            Log::info('[CentauroProductProcessor] Product has no custom_4 attribute, set as standalone parent', [
                'product_id' => $product->id,
            ]);
            
            return;
        }

        $custom4Value = $custom4Attribute->description;

        // Find all products from the same store with the same custom_4 value
        $productsInGroup = Product::where('store_id', $product->store_id)
            ->whereHas('attributes', function ($query) use ($custom4Value) {
                $query->where('key', 'custom_4')->where('description', $custom4Value);
            })
            ->get();

        // The product with the smallest ID is the parent
        $parentId = $productsInGroup->min('id');

        // Set is_parent accordingly
        if ($product->id == $parentId) {
            $product->is_parent = 0; // This is the parent
            
            Log::info('[CentauroProductProcessor] Product is the parent of the group', [
                'product_id' => $product->id,
                'custom_4' => $custom4Value,
            ]);

        } else {
            $product->is_parent = $parentId; // This is a child, reference the parent

            Log::info('[CentauroProductProcessor] Product is a child of the group', [
                'product_id' => $product->id,
                'parent_id' => $parentId,
                'custom_4' => $custom4Value,
            ]);
        }

        $product->save();

        Log::info('[CentauroProductProcessor] Finished processing product for Centauro', [
            'product_id' => $product->id,
            'is_parent' => $product->is_parent,
        ]);
    }
}
