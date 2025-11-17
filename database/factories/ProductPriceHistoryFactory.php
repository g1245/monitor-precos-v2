<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPriceHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPriceHistory>
 */
class ProductPriceHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = ProductPriceHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'price' => fake()->randomFloat(2, 10, 5000),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Create price history for a specific product.
     */
    public function forProduct(Product $product): Factory
    {
        return $this->state(function (array $attributes) use ($product) {
            return [
                'product_id' => $product->id,
            ];
        });
    }

    /**
     * Create historical prices with variation from base price.
     */
    public function withPriceVariation(float $basePrice, float $variationPercent = 0.20): Factory
    {
        return $this->state(function (array $attributes) use ($basePrice, $variationPercent) {
            $variation = $basePrice * $variationPercent;
            $minPrice = $basePrice - $variation;
            $maxPrice = $basePrice + $variation;
            
            return [
                'price' => fake()->randomFloat(2, max($minPrice, 1), $maxPrice),
            ];
        });
    }
}