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
        // Clear existing price history
        ProductPriceHistory::truncate();

        // Get all existing products
        $products = Product::all();

        foreach ($products as $product) {
            // Create 3-5 price history records for each product
            $historyCount = rand(3, 5);
            
            ProductPriceHistory::factory()
                ->count($historyCount)
                ->forProduct($product)
                ->withPriceVariation($product->price, 0.25) // 25% variation
                ->create();
        }
    }
}