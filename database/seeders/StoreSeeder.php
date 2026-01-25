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
        $stores = [
            [
                'name' => 'Magazine Luiza',
                'internal_name' => 'Magazine Luiza BR'
            ],
            [
                'name' => 'Americanas',
                'internal_name' => 'Americanas BR'
            ],
            [
                'name' => 'Mercado Livre',
                'internal_name' => 'Mercado Livre BR'
            ],
            [
                'name' => 'Amazon',
                'internal_name' => 'Amazon BR'
            ],
            [
                'name' => 'Casas Bahia',
                'internal_name' => 'Casas Bahia BR'
            ],
        ];

        foreach ($stores as $storeData) {
            Store::create(
                $storeData
            );
        }

        $this->command->info('Stores created successfully!');
    }
}
