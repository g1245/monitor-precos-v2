<?php

namespace App\Console\Commands;

use App\Jobs\Product\ProcessProductJob;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:process
                            {--product= : The ID of the product to process}
                            {--store=   : The ID of the store whose products will be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch processing job(s) for a single product or all products from a store';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $productId = $this->option('product');
        $storeId   = $this->option('store');

        if (!$productId && !$storeId) {
            $this->error('You must provide either --product=<id> or --store=<id>.');

            return self::FAILURE;
        }

        if ($productId) {
            return $this->dispatchSingleProduct((int) $productId);
        }

        return $this->dispatchStoreProducts((int) $storeId);
    }

    /**
     * Dispatch a processing job for a single product.
     */
    private function dispatchSingleProduct(int $productId): int
    {
        if (!Product::where('id', $productId)->exists()) {
            Log::warning('Product not found for processing', ['product_id' => $productId]);
            $this->error("Product [{$productId}] not found.");

            return self::FAILURE;
        }

        ProcessProductJob::dispatch($productId);

        $this->info("Job dispatched for product [{$productId}].");

        return self::SUCCESS;
    }

    /**
     * Dispatch a processing job for each product in the given store.
     */
    private function dispatchStoreProducts(int $storeId): int
    {
        $store = Store::find($storeId);

        if (!$store) {
            Log::warning('Store not found for processing', ['store_id' => $storeId]);
            $this->error("Store [{$storeId}] not found.");

            return self::FAILURE;
        }

        $productIds = Product::where('store_id', $storeId)->pluck('id');

        if ($productIds->isEmpty()) {
            $this->warn("No products found for store [{$store->name}].");

            return self::SUCCESS;
        }

        foreach ($productIds as $id) {
            ProcessProductJob::dispatch($id);
        }

        $this->info("Dispatched {$productIds->count()} job(s) for store [{$store->name}].");

        return self::SUCCESS;
    }
}