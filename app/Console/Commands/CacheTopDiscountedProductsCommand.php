<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheTopDiscountedProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-top-discounted-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache top 30 products with highest discounts for welcome page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching top discounted products...');

        // Get top 30 products with highest discount percentage
        // Only active products with valid discount_percentage
        $products = Product::query()
            ->active()
            ->where('is_parent', 0)
            ->whereNotNull('discount_percentage')
            ->where('discount_percentage', '>', 0)
            ->with('store:id,name,logo')
            ->orderBy('discount_percentage', 'desc')
            ->limit(30)
            ->get([
                'id',
                'store_id',
                'name',
                'price',
                'price_regular',
                'image_url',
                'discount_percentage',
            ]);

        // Cache products for 30 minutes
        Cache::put('welcome.top_discounted_products', $products, now()->addMinutes(30));

        $this->info("Cached {$products->count()} top discounted products successfully.");

        return Command::SUCCESS;
    }
}
