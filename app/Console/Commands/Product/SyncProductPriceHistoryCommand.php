<?php

namespace App\Console\Commands\Product;

use App\Models\Product;
use App\Models\Store;
use App\Services\ProductPriceHistorySyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncProductPriceHistoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-product-price-history 
                            {--store_id= : Filter products by store ID}
                            {--sku= : Sync specific product by SKU}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize product price history from external API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = now();
        $storeId = $this->option('store_id');
        $sku = $this->option('sku');

        // Validate mutual exclusivity
        if ($storeId && $sku) {
            $this->error('Cannot use --store_id and --sku options together. Please use only one.');
            return Command::FAILURE;
        }

        Log::channel('sync-price-history')->info('Price history sync process started', [
            'started_at' => $startTime->format('Y-m-d H:i:s'),
            'store_id' => $storeId,
            'sku' => $sku,
        ]);

        // Handle specific SKU sync
        if ($sku) {
            return $this->syncSingleProduct($sku, $startTime);
        }

        // Handle store filter or all products
        return $this->syncMultipleProducts($storeId, $startTime);
    }

    /**
     * Sync price history for a single product by SKU.
     *
     * @param string $sku
     * @param \Illuminate\Support\Carbon $startTime
     * @return int
     */
    private function syncSingleProduct(string $sku, $startTime): int
    {
        $this->info("Searching for product with SKU: {$sku}");

        $product = Product::where('sku', $sku)
            ->active()
            ->first();

        if (!$product) {
            $this->error("Product not found with SKU: {$sku}");

            Log::channel('sync-price-history')->error('Product not found', [
                'sku' => $sku,
                'started_at' => $startTime->format('Y-m-d H:i:s'),
                'finished_at' => now()->format('Y-m-d H:i:s'),
            ]);

            return Command::FAILURE;
        }

        $this->info("Syncing price history for: {$product->name} (ID: {$product->id})");

        $result = ProductPriceHistorySyncService::syncPriceHistoryForProduct($product);

        if ($result['success']) {
            $this->info("✓ {$result['message']}");
        } else {
            $this->warn("✗ {$result['message']}");
        }

        $endTime = now();

        Log::channel('sync-price-history')->info('Single product sync completed', [
            'product_id' => $product->id,
            'sku' => $sku,
            'started_at' => $startTime->format('Y-m-d H:i:s'),
            'finished_at' => $endTime->format('Y-m-d H:i:s'),
            'duration_seconds' => $endTime->diffInSeconds($startTime),
            'synced_count' => $result['synced_count'],
        ]);

        return Command::SUCCESS;
    }

    /**
     * Sync price history for multiple products.
     *
     * @param int|null $storeId
     * @param \Illuminate\Support\Carbon $startTime
     * @return int
     */
    private function syncMultipleProducts(?int $storeId, $startTime): int
    {
        // If store_id provided, validate it exists
        if ($storeId) {
            $store = Store::find($storeId);

            if (!$store) {
                $this->error("Store not found with ID: {$storeId}");

                Log::channel('sync-price-history')->error('Store not found', [
                    'store_id' => $storeId,
                    'started_at' => $startTime->format('Y-m-d H:i:s'),
                    'finished_at' => now()->format('Y-m-d H:i:s'),
                ]);

                return Command::FAILURE;
            }

            $this->info("Syncing price history for products from store: {$store->name} (ID: {$storeId})");
        } else {
            $this->info("Syncing price history for all active products");
        }

        // Build query
        $query = Product::query()->active();

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        $totalProducts = $query->count();

        if ($totalProducts === 0) {
            $this->warn('No active products found to sync.');
            return Command::SUCCESS;
        }

        $this->info("Found {$totalProducts} products to sync");

        $processedCount = 0;
        $successCount = 0;
        $failureCount = 0;
        $totalSyncedEntries = 0;

        $progressBar = $this->output->createProgressBar($totalProducts);
        $progressBar->start();

        // Process in chunks to avoid memory issues
        $query->chunk(50, function ($products) use (&$processedCount, &$successCount, &$failureCount, &$totalSyncedEntries, $progressBar) {
            foreach ($products as $product) {
                $result = ProductPriceHistorySyncService::syncPriceHistoryForProduct($product);

                $processedCount++;

                if ($result['success']) {
                    $successCount++;
                    $totalSyncedEntries += $result['synced_count'];
                } else {
                    $failureCount++;
                }

                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        $endTime = now();
        $duration = $endTime->diffInSeconds($startTime);

        // Display summary
        $this->info("=== Sync Summary ===");
        $this->info("Total products processed: {$processedCount}");
        $this->info("Successful syncs: {$successCount}");
        $this->info("Failed syncs: {$failureCount}");
        $this->info("Total price entries synced: {$totalSyncedEntries}");
        $this->info("Duration: {$duration} seconds");

        Log::channel('sync-price-history')->info('Bulk price history sync completed', [
            'store_id' => $storeId,
            'started_at' => $startTime->format('Y-m-d H:i:s'),
            'finished_at' => $endTime->format('Y-m-d H:i:s'),
            'duration_seconds' => $duration,
            'total_products' => $processedCount,
            'successful' => $successCount,
            'failed' => $failureCount,
            'total_synced_entries' => $totalSyncedEntries,
        ]);

        return Command::SUCCESS;
    }
}
