<?php

namespace App\Services;

use App\Models\Product;
use App\Services\ProductProcessors\BaseProductProcessor;
use App\Services\ProductProcessors\DefaultProductProcessor;
use App\Services\ProductProcessors\ProductProcessorInterface;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class ProductProcessorService
{
    /**
     * Auto-discovered store-specific processors (excludes the fallback).
     *
     * @var array<ProductProcessorInterface>
     */
    protected array $processors;

    /**
     * Fallback processor used when no dedicated processor is found.
     */
    protected ProductProcessorInterface $fallback;

    /**
     * Create a new product processor service instance.
     *
     * Processors are discovered automatically from the ProductProcessors directory.
     * Any class that extends BaseProductProcessor (and is not the DefaultProductProcessor)
     * will be registered without requiring manual additions to this file.
     */
    public function __construct()
    {
        $this->fallback   = new DefaultProductProcessor;
        $this->processors = $this->discoverProcessors();
    }

    /**
     * Process a product by finding the appropriate processor for its store,
     * falling back to the default processor when none is found.
     *
     * @param Product $product The product to process
     * @return void
     */
    public function process(Product $product): void
    {
        $processor = $this->findProcessor($product->store_id) ?? $this->fallback;

        try {
            $processor->process($product);

            Log::info('Product processed successfully', [
                'product_id' => $product->id,
                'store_id'   => $product->store_id,
                'processor'  => get_class($processor),
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing product', [
                'product_id' => $product->id,
                'store_id'   => $product->store_id,
                'error'      => $e->getMessage(),
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

    /**
     * Discover all concrete processor classes inside the ProductProcessors directory.
     *
     * A class is included when it:
     *  - has a .php extension
     *  - is a concrete class (not abstract, not an interface)
     *  - implements ProductProcessorInterface
     *  - is not BaseProductProcessor or DefaultProductProcessor (the fallback)
     *
     * @return array<ProductProcessorInterface>
     */
    protected function discoverProcessors(): array
    {
        $namespace = 'App\\Services\\ProductProcessors\\';
        $directory = app_path('Services/ProductProcessors');

        $excluded = [
            ProductProcessorInterface::class,
            BaseProductProcessor::class,
            DefaultProductProcessor::class,
        ];

        $processors = [];

        foreach (new \DirectoryIterator($directory) as $file) {
            if ($file->isDot() || $file->getExtension() !== 'php') {
                continue;
            }

            $class = $namespace . $file->getBasename('.php');

            if (!class_exists($class) || in_array($class, $excluded, true)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if ($reflection->isAbstract() || $reflection->isInterface()) {
                continue;
            }

            if (!$reflection->implementsInterface(ProductProcessorInterface::class)) {
                continue;
            }

            $processors[] = new $class;
        }

        return $processors;
    }
}
