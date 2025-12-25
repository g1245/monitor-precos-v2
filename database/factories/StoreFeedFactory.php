<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\StoreFeed;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreFeed>
 */
class StoreFeedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreFeed::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sources = [
            'Google Shopping',
            'Facebook Catalog',
            'Instagram Shopping',
            'Mercado Livre',
            'XML Feed',
            'CSV Feed',
            'API REST',
            'Shopify',
            'WooCommerce',
            'Magento',
        ];

        return [
            'store_id' => Store::factory(),
            'source' => fake()->randomElement($sources),
            'download_url' => fake()->url() . '/feed.xml',
            'last_updated_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that the feed has never been updated.
     */
    public function neverUpdated(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_updated_at' => null,
        ]);
    }

    /**
     * Indicate that the feed was recently updated.
     */
    public function recentlyUpdated(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_updated_at' => now()->subHours(fake()->numberBetween(1, 24)),
        ]);
    }
}
