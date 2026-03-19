<?php

namespace App\Console\Commands\Product;

use App\Models\Product;
use Illuminate\Console\Command;

class ReindexOldPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reindex-old-price
                            {store_id? : Optional store ID to filter products}
                            {product_id? : Optional product ID to reindex a single product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex old_price from products_prices_histories by store_id and/or product_id';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $storeIdArgument = $this->argument('store_id');
        $productIdArgument = $this->argument('product_id');
        $productId = null;
        $storeId = null;

        if ($storeIdArgument !== null) {
            $storeId = (int) $storeIdArgument;

            if ($storeId <= 0) {
                $this->error('The store_id argument must be a positive integer when provided.');

                return Command::FAILURE;
            }
        }

        if ($productIdArgument !== null) {
            $productId = (int) $productIdArgument;

            if ($productId <= 0) {
                $this->error('The product_id argument must be a positive integer when provided.');

                return Command::FAILURE;
            }
        }

        if ($storeId === null && $productId === null) {
            $this->error('You must provide at least one argument: store_id or product_id.');

            return Command::FAILURE;
        }

        $productsQuery = Product::query()
            ->when($storeId !== null, fn ($query) => $query->where('store_id', $storeId))
            ->when($productId !== null, fn ($query) => $query->whereKey($productId));

        $totalProducts = (clone $productsQuery)->count();

        if ($totalProducts === 0) {
            $this->warn('No products found for the provided parameters.');

            return Command::SUCCESS;
        }

        $this->info("Starting old_price reindex for {$totalProducts} product(s)...");

        $processed = 0;
        $updated = 0;

        $productsQuery
            ->orderBy('id')
            ->chunkById(200, function ($products) use (&$processed, &$updated): void {
                foreach ($products as $product) {
                    $processed++;

                    $reindexedOldPrice = $product->priceHistories()
                        ->where('price', '<>', $product->price)
                        ->orderByDesc('created_at')
                        ->orderByDesc('id')
                        ->value('price');

                    $currentOldPrice = $product->old_price !== null
                        ? number_format((float) $product->old_price, 4, '.', '')
                        : null;

                    $newOldPrice = $reindexedOldPrice !== null
                        ? number_format((float) $reindexedOldPrice, 4, '.', '')
                        : null;

                    if ($currentOldPrice !== $newOldPrice) {
                        $product->updateQuietly([
                            'old_price' => $reindexedOldPrice,
                        ]);

                        $updated++;
                    }
                }
            });

        $this->info("Reindex completed. Processed {$processed} product(s), updated {$updated} old_price value(s).");

        return Command::SUCCESS;
    }
}
