<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            for ($i = 1; $i <= 4; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'https://placehold.co/800x800/e2e8f0/1e293b?text='.urlencode($product->name.' - '.$i),
                    'order' => $i,
                ]);
            }
        }
    }
}
