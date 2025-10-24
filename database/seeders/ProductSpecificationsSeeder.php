<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Database\Seeder;

class ProductSpecificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        $commonSpecs = [
            ['name' => 'Modelo', 'value' => 'XYZ-2024'],
            ['name' => 'Cor', 'value' => 'Preto'],
            ['name' => 'Peso', 'value' => '1.5 kg'],
            ['name' => 'Dimensões', 'value' => '30 x 20 x 10 cm'],
            ['name' => 'Material', 'value' => 'Plástico ABS'],
            ['name' => 'Garantia', 'value' => '12 meses'],
            ['name' => 'Voltagem', 'value' => 'Bivolt'],
            ['name' => 'País de origem', 'value' => 'Brasil'],
        ];

        foreach ($products as $product) {
            $order = 1;
            foreach ($commonSpecs as $spec) {
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'name' => $spec['name'],
                    'value' => $spec['value'],
                    'order' => $order++,
                ]);
            }
        }
    }
}
