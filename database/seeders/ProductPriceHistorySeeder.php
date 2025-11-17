<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPriceHistory;
use Illuminate\Database\Seeder;

class ProductPriceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing products
        $products = Product::take(5)->get();

        foreach ($products as $product) {
            // Create 30-90 days of price history for each product
            $historyCount = rand(30, 90);
            
            ProductPriceHistory::factory()
                ->count($historyCount)
                ->forProduct($product)
                ->withPriceVariation($product->price, 0.25) // 25% variation
                ->create();
        }
    }
}