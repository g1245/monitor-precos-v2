<?php

namespace App\Console\Commands\Product;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReindexRecordedPricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reindex-recorded-prices
                            {--store_id= : Store ID to reindex all its products}
                            {--product_id= : Single product ID to reindex}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex highest_recorded_price and lowest_recorded_price from price history for a product or an entire store';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $storeIdOption   = $this->option('store_id');
        $productIdOption = $this->option('product_id');
        $storeId         = null;
        $productId       = null;

        if ($storeIdOption !== null) {
            $storeId = (int) $storeIdOption;

            if ($storeId <= 0) {
                $this->error('The --store_id option must be a positive integer.');

                return Command::FAILURE;
            }
        }

        if ($productIdOption !== null) {
            $productId = (int) $productIdOption;

            if ($productId <= 0) {
                $this->error('The --product_id option must be a positive integer.');

                return Command::FAILURE;
            }
        }

        $productsQuery = Product::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->when($productId !== null, fn ($q) => $q->whereKey($productId));

        $total = (clone $productsQuery)->count();

        if ($total === 0) {
            $this->warn('No products found for the given parameters.');

            return Command::SUCCESS;
        }

        $scope = match (true) {
            $productId !== null => "product ID {$productId}",
            $storeId !== null   => "store ID {$storeId}",
            default             => 'all stores',
        };

        $this->info("Reindexing recorded prices for {$total} product(s) [{$scope}]...");

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $processed = 0;
        $updated   = 0;

        $productsQuery
            ->orderBy('id')
            ->chunkById(200, function ($products) use (&$processed, &$updated, $progressBar): void {
                foreach ($products as $product) {
                    $aggregates = $product->priceHistories()
                        ->selectRaw('MAX(price) as highest, MIN(price) as lowest')
                        ->first();

                    if ($aggregates === null || $aggregates->highest === null) {
                        $progressBar->advance();
                        $processed++;
                        continue;
                    }

                    DB::table('products')
                        ->where('id', $product->id)
                        ->update([
                            'highest_recorded_price' => $aggregates->highest,
                            'lowest_recorded_price'  => $aggregates->lowest,
                        ]);

                    $updated++;
                    $processed++;
                    $progressBar->advance();
                }
            });

        $progressBar->finish();
        
        $this->newLine(2);

        $this->info("Reindex completed. Processed: {$processed} | Updated: {$updated} | Skipped (no history): " . ($processed - $updated));

        return Command::SUCCESS;
    }
}
