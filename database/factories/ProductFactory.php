<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'permalink' => uniqid(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'sku' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{6}'),
            'brand' => $this->faker->company(),
            'image_url' => $this->faker->imageUrl(640, 480, 'products', true),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Create an active product.
     */
    public function active(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    /**
     * Create an inactive product.
     */
    public function inactive(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Create a product with a specific price range.
     */
    public function priceRange(float $min, float $max): static
    {
        return $this->state(function (array $attributes) use ($min, $max) {
            return [
                'price' => $this->faker->randomFloat(2, $min, $max),
            ];
        });
    }

    /**
     * Create a product without SKU.
     */
    public function withoutSku(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'sku' => null,
            ];
        });
    }
}