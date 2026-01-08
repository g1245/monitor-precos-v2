<?php

namespace App\Console\Commands\Product;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncProductByStoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-product-by-store {store}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize products by store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $store = $this->argument('store');

        // Logic to sync products by store goes here
        $page = 1;
        $totalPages = 0;

        do {
            $products = $this->fetchProducts($store, $page++);

            if (empty($products)) {
                $this->error("Failed to fetch products for store: {$store} on page: {$page}");

                return Command::FAILURE;
            }

            $totalPages = $products['totalPages'];

            $this->info("Fetched page {$page} of products for store: {$store}");
            $this->info("Total Pages: {$totalPages}");

            foreach ($products['data'] as $product) {
                // Here you would typically save or update the product in your database
                $this->info("Processing product ID: {$product['product_name']} for store: {$store}");
            }
        } while ($page < $totalPages);

        $this->info("Products synchronized for store: {$store}");

        return Command::SUCCESS;
    }


    private function fetchProducts(string $storeName, int $page = 1, int $limit = 100): array
    {
        $request = Http::withHeaders([
            'x-api-key' => 'your-secure-api-key-here'
        ])->get('http://host.docker.internal:3000/products', [
            'store' => $storeName,
            'page' => $page,
            'limit' => $limit,
        ]);

        if ($request->failed()) {
            return [];
        }

        return $request->json();
    }
}
