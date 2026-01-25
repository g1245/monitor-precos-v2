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
                'url' => 'https://www.magazineluiza.com.br',
            ],
            [
                'name' => 'Americanas',
                'slug' => 'americanas',
                'url' => 'https://www.americanas.com.br',
            ],
            [
                'name' => 'Mercado Livre',
                'slug' => 'mercado-livre',
                'url' => 'https://www.mercadolivre.com.br',
            ],
            [
                'name' => 'Amazon',
                'slug' => 'amazon',
                'url' => 'https://www.amazon.com.br',
            ],
            [
                'name' => 'Casas Bahia',
                'slug' => 'casas-bahia',
                'url' => 'https://www.casasbahia.com.br',
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
