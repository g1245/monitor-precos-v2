<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Department;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncTopDiscountedProductsToDepartmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-top-discounted-products-to-department';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products with biggest price reductions to Department (Top Discounts)';

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

        // Get or verify Department exists
        $department = Department::find(self::TOP_DISCOUNTS_DEPARTMENT_ID);
        
        if (!$department) {
            $this->error('Department with ID ' . self::TOP_DISCOUNTS_DEPARTMENT_ID . ' does not exist. Please create it first.');

            return Command::FAILURE;
        }

        // Step 1: Detach all products from Department
        $removedCount = DB::table('departments_products')
            ->where('department_id', self::TOP_DISCOUNTS_DEPARTMENT_ID)
            ->delete();

        $this->info('Removing all products from Department...');
        $this->info("Removed {$removedCount} products from Department.");

        // Step 2: Calculate price reductions and get top discounted products
        $this->info('Calculating price reductions from price history...');

        $topDiscountedProducts = $this->getTopDiscountedProducts();

        if ($topDiscountedProducts->isEmpty()) {
            $this->warn('No products with price reductions found.');

            return Command::SUCCESS;
        }

        $this->info("Found {$topDiscountedProducts->count()} products with price reductions.");

        // Step 3: Attach products to Department
        $this->info('Associating products to Department...');
        
        $department->products()->attach(
            $topDiscountedProducts->pluck('id')->toArray()
        );

        $this->info("Successfully synced {$topDiscountedProducts->count()} products to Department.");
        $this->info('Synchronization completed successfully!');

        return Command::SUCCESS;
    }

    /**
     * Get products with biggest price reductions based on old_price vs price fields.
     *
     * @param int|null $limit
     * @return \Illuminate\Support\Collection
     */
    private function getTopDiscountedProducts()
    {
        return Product::query()
            ->fromPublicStore()
            ->parentProducts()
            ->withRecentPriceChange()
            ->get();
    }
}
