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
            $this->error('Department with ID 1 does not exist. Please create it first.');
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
        // Subquery to get the previous price (second to last price) for each product
        $previousPricesSubquery = DB::table('products_prices_histories as pph1')
            ->select('pph1.product_id', 'pph1.price as previous_price')
            ->whereRaw('pph1.created_at = (
                SELECT MAX(pph2.created_at) 
                FROM products_prices_histories as pph2 
                WHERE pph2.product_id = pph1.product_id 
                AND pph2.created_at < (
                    SELECT MAX(pph3.created_at) 
                    FROM products_prices_histories as pph3 
                    WHERE pph3.product_id = pph1.product_id
                )
            )');

        // Main query to get products with price reductions
        $products = Product::query()
            ->select([
                'products.id',
                'products.name',
                'products.price',
                'products.price_regular',
                'products.discount_percentage',
                'previous_prices.previous_price',
                DB::raw('ROUND(((previous_prices.previous_price - products.price) / previous_prices.previous_price * 100), 2) as price_reduction_percentage'),
                DB::raw('(previous_prices.previous_price - products.price) as price_reduction_value'),
            ])
            ->joinSub($previousPricesSubquery, 'previous_prices', function ($join) {
                $join->on('products.id', '=', 'previous_prices.product_id');
            })
            ->active()
            ->where('products.price', '<', DB::raw('previous_prices.previous_price')) // Only products with price reduction
            ->whereRaw('previous_prices.previous_price > 0') // Avoid division by zero
            ->orderByDesc('price_reduction_percentage')
            ->limit($limit)
            ->get();

        return $products;
    }
}
