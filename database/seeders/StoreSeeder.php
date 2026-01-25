<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 sample stores
        $stores = [
            [
                'name' => 'Magazine Luiza',
                'slug' => 'magazine-luiza',
            ],
            [
                'name' => 'Americanas',
                'slug' => 'americanas',
            ],
            [
                'name' => 'Mercado Livre',
                'slug' => 'mercado-livre',
            ],
            [
                'name' => 'Amazon',
                'slug' => 'amazon',
            ],
            [
                'name' => 'Casas Bahia',
                'slug' => 'casas-bahia',
            ],
        ];

        foreach ($stores as $storeData) {
            Store::firstOrCreate(
                ['slug' => $storeData['slug']],
                $storeData
            );
        }

        $this->command->info('Stores created successfully!');
    }
}
