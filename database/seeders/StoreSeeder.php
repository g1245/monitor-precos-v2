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
                'name' => 'Amazon',
                'internal_name' => 'Amazon BR'
            ],
            [
                'name' => 'Casas Bahia',
                'internal_name' => 'Casas Bahia BR'
            ],
            [
                'name' => 'Nike',
                'internal_name' => 'nike',
                'metadata' => [
                    'SyncStoreName' => 'Nike BR'
                ]
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
