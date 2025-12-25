<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storeName = fake()->company();
        
        return [
            'name' => $storeName,
            'logo' => fake()->imageUrl(200, 200, 'business', true, $storeName),
            'full_url' => fake()->url(),
            'metadata' => [
                'category' => fake()->randomElement(['Electronics', 'Fashion', 'Home', 'Sports', 'Books']),
                'founded' => fake()->year(),
                'rating' => fake()->randomFloat(1, 3.0, 5.0),
            ],
        ];
    }

    /**
     * Indicate that the store has no logo.
     */
    public function withoutLogo(): static
    {
        return $this->state(fn (array $attributes) => [
            'logo' => null,
        ]);
    }

    /**
     * Indicate that the store has no metadata.
     */
    public function withoutMetadata(): static
    {
        return $this->state(fn (array $attributes) => [
            'metadata' => null,
        ]);
    }
}
