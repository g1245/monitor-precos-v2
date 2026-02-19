<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Department;
use App\Models\ProductPriceHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncTopDiscountedProductsToDepartmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-top-discounted-products-to-department 
                            {--limit=50 : Number of top discounted products to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products with biggest price reductions to Department 1 (Top Discounts)';

    /**
     * The department ID for top discounts.
     */
    private const TOP_DISCOUNTS_DEPARTMENT_ID = 154;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting synchronization of top discounted products...');

        $limit = (int) $this->option('limit');

        // Get or verify Department 1 exists
        $department = Department::find(self::TOP_DISCOUNTS_DEPARTMENT_ID);
        
        if (!$department) {
            $this->error('Department with ID ' . self::TOP_DISCOUNTS_DEPARTMENT_ID . ' does not exist. Please create it first.');
            return Command::FAILURE;
        }

        $this->info("Department: {$department->name} (ID: {$department->id})");

        // Step 1: Detach all products from Department 1
        $this->info('Removing all products from Department 1...');
        $removedCount = DB::table('departments_products')
            ->where('department_id', self::TOP_DISCOUNTS_DEPARTMENT_ID)
            ->delete();
        $this->info("Removed {$removedCount} products from Department 1.");

        // Step 2: Calculate price reductions and get top discounted products
        $this->info('Calculating price reductions from price history...');
        $topDiscountedProducts = $this->getTopDiscountedProducts($limit);

        if ($topDiscountedProducts->isEmpty()) {
            $this->warn('No products with price reductions found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$topDiscountedProducts->count()} products with price reductions.");

        // Step 3: Attach products to Department 1
        $this->info('Associating products to Department 1...');
        $productIds = $topDiscountedProducts->pluck('id')->toArray();
        
        $department->products()->attach($productIds);

        $this->info("Successfully synced {$topDiscountedProducts->count()} products to Department 1.");

        // Display summary
        $this->newLine();
        $this->table(
            ['Product ID', 'Name', 'Current Price', 'Previous Price', 'Reduction %'],
            $topDiscountedProducts->take(10)->map(function ($product) {
                return [
                    $product->id,
                    substr($product->name, 0, 40) . '...',
                    'R$ ' . number_format($product->price, 2, ',', '.'),
                    'R$ ' . number_format($product->previous_price, 2, ',', '.'),
                    number_format($product->price_reduction_percentage, 2) . '%',
                ];
            })->toArray()
        );

        $this->info('Synchronization completed successfully!');

        return Command::SUCCESS;
    }

    /**
     * Get products with biggest price reductions based on price history.
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    private function getTopDiscountedProducts(int $limit)
    {
        // Query to get products with the biggest discounts
        $products = Product::query()
            ->select([
                'products.id',
                'products.name',
                'products.price',
                'products.price_regular',
                'products.discount_percentage',
                DB::raw('MAX(products_prices_histories.price) as previous_price'),
                DB::raw('ROUND((1 - products.price / MAX(products_prices_histories.price)) * 100, 2) as price_reduction_percentage'),
                DB::raw('(MAX(products_prices_histories.price) - products.price) as price_reduction_value'),
            ])
            ->leftJoin('products_prices_histories', 'products_prices_histories.product_id', '=', 'products.id')
            ->active()
            ->groupBy('products.id')
            ->havingRaw('MAX(products_prices_histories.price) <> MIN(products_prices_histories.price)')
            ->having('products.price', '=', DB::raw('MIN(products_prices_histories.price)'))
            ->orderByDesc('price_reduction_percentage')
            ->limit($limit)
            ->get();

        return $products;
    }
}
