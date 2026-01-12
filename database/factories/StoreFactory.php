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
            'metadata' => [
                'external_name' => $storeName . ' BR',
                'region' => 'BR',
            ],
        ];
    }
}
