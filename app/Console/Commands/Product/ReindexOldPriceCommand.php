<?php

namespace App\Console\Commands\Product;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReindexOldPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reindex-old-price
                            {--store_id= : Optional store ID to filter products}
                            {--product_id= : Optional product ID to reindex a single product}';

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
        $storeIdOption = $this->option('store_id');
        $productIdOption = $this->option('product_id');
        $productId = null;
        $storeId = null;

        if ($storeIdOption !== null) {
            $storeId = (int) $storeIdOption;

            if ($storeId <= 0) {
                $this->error('The --store_id option must be a positive integer when provided.');

                return Command::FAILURE;
            }
        }

        if ($productIdOption !== null) {
            $productId = (int) $productIdOption;

            if ($productId <= 0) {
                $this->error('The --product_id option must be a positive integer when provided.');

                return Command::FAILURE;
            }
        }

        if ($storeId === null && $productId === null) {
            $this->error('You must provide at least one option: --store_id or --product_id.');

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

        $resetCount = (clone $productsQuery)
            ->toBase()
            ->update(['old_price' => null]);

        $this->info("Reset old_price for {$resetCount} product(s) before reindex.");

        $this->info("Starting old_price reindex for {$totalProducts} product(s) using last 3 days history...");

        $processed = 0;
        $updated = 0;

        $productsQuery
            ->orderBy('id')
            ->chunkById(200, function ($products) use (&$processed, &$updated): void {
                foreach ($products as $product) {
                    $processed++;

                    $reindexedOldPrice = $product->priceHistories()
                        ->where('created_at', '>=', now()->subDays(3))
                        ->where('price', '<>', $product->price)
                        ->orderByDesc('created_at')
                        ->orderByDesc('id')
                        ->value('price');

                    $newOldPrice = $reindexedOldPrice !== null
                        ? number_format((float) $reindexedOldPrice, 4, '.', '')
                        : null;

                    if ($newOldPrice !== null) {
                        DB::table('products')
                            ->where('id', $product->id)
                            ->update([
                                'old_price' => $reindexedOldPrice,
                            ]);

                        $updated++;
                    }
                }
            });

        $this->info("Reindex completed. Processed {$processed} product(s), updated {$updated} old_price value(s) from last 3 days history.");

        return Command::SUCCESS;
    }
}
