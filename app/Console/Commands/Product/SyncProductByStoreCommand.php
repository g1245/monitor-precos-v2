<?php

namespace App\Console\Commands\Product;

use App\Models\Store;
use App\Dto\ProductDto;
use App\Dto\ProductAttributeDto;
use Illuminate\Console\Command;
use App\Services\ProductService;
use App\Services\ProductAttributeService;
use Illuminate\Support\Facades\Http;

class SyncProductByStoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-product-by-store {name}';

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
        $storeName = $this->argument('name');

        $store = Store::query()
            ->whereJsonContains('metadata->SyncStoreName', $storeName)
            ->first();

        if (!$store) {
            $this->error("Store not found: {$storeName}");

            return Command::FAILURE;
        }

        // Logic to sync products by store goes here
        $page = 1;
        $totalPages = 0;

        do {
            $products = $this->fetchProducts($storeName, $page++);

            if (empty($products)) {
                $this->error("Failed to fetch products for store: {$store} on page: {$page}");

                return Command::FAILURE;
            }

            $totalPages = $products['totalPages'];

            $this->info("Fetched page {$page} of products for store: {$store->name}");
            $this->info("Total Pages: {$totalPages}");

            foreach ($products['data'] as $product) {
                // Here you would typically save or update the product in your database
                $this->info("Processing product ID: " . $product['aw_product_id'] . " for store: {$store->name}");

                $savedProduct = ProductService::createOrUpdate(
                    new ProductDto(
                        storeId: $store->id,
                        name: $product['product_name'],
                        description: $product['description'] ?? null,
                        price: isset($product['search_price']) ? (float) $product['search_price'] : null,
                        priceRegular: isset($product['product_price_old']) ? (float) $product['product_price_old'] : (isset($product['base_price']) ? (float) str_replace('R$ ', '', $product['base_price']) : null),
                        sku: $product['aw_product_id'],
                        brand: $product['brand_name'] ?? null,
                        imageUrl: $product['merchant_image_url'],
                        deepLink: $product['aw_deep_link'] ?? null,
                        externalLink: $product['merchant_deep_link'] ?? null,
                    )
                );

                // Record price history if price has changed
                if ($savedProduct->shouldRecordPriceHistory()) {
                    $savedProduct->addPriceHistory($savedProduct->price);
                }

                // Sync product attributes after saving the product
                ProductAttributeService::sync(
                    ProductAttributeDto::fromApiData($savedProduct->id, $product)
                );

                // Sync product departments from category path
                $categoryPath = $product['merchant_category'] ?? $product['merchant_product_category_path'] ?? null;
                
                if ($categoryPath) {
                    $this->info("Syncing departments for product ID: " . $savedProduct->id);
                    $this->info("Department Path: " . $categoryPath);

                    ProductService::syncDepartmentsFromPath(
                        $savedProduct->id,
                        $categoryPath
                    );
                }
            }
        } while ($page < $totalPages);

        $this->info("Products synchronized for store: {$store}");

        return Command::SUCCESS;
    }


    private function fetchProducts(string $storeName, int $page = 1, int $limit = 100): array
    {
        $request = Http::withHeaders([
            'x-api-key' => config('services.awin.token')
        ])->get(config('services.awin.url') . '/products', [
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
