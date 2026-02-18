<?php

namespace App\Services;

use App\Models\Product;
use App\Services\ProductProcessors\ProductProcessorInterface;
use App\Services\ProductProcessors\CentauroProductProcessor;
use App\Services\ProductProcessors\NikeProductProcessor;
use Illuminate\Support\Facades\Log;

class ProductProcessorService
{
    /**
     * List of available product processors.
     *
     * @var array<ProductProcessorInterface>
     */
    protected array $processors;

    /**
     * Create a new product processor service instance.
     */
    public function __construct()
    {
        $this->processors = [
            new CentauroProductProcessor(),
            new NikeProductProcessor(),
        ];
    }

    /**
     * Process a product by finding the appropriate processor for its store.
     *
     * @param Product $product The product to process
     * @return void
     */
    public function process(Product $product): void
    {
        $processor = $this->findProcessor($product->store_id);

        if (!$processor) {
            Log::info('No processor found for store', [
                'product_id' => $product->id,
                'store_id' => $product->store_id,
            ]);
            return;
        }

        try {
            $processor->process($product);
            
            Log::info('Product processed successfully', [
                'product_id' => $product->id,
                'store_id' => $product->store_id,
                'processor' => get_class($processor),
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing product', [
                'product_id' => $product->id,
                'store_id' => $product->store_id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Find the appropriate processor for the given store.
     *
     * @param int $storeId The store ID
     * @return ProductProcessorInterface|null
     */
    protected function findProcessor(int $storeId): ?ProductProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->canHandle($storeId)) {
                return $processor;
            }
        }

        return null;
    }
}
