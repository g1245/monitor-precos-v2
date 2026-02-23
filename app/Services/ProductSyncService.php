<?php

namespace App\Services;

use App\Dto\ProductAttributeDto;
use App\Dto\ProductDto;
use App\Jobs\SyncProductsForStoreJob;
use App\Models\Store;
use App\Services\ProductAttributeService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductSyncService
{
    /**
     * Sync products for a given store.
     *
     * @param Store $store
     * @param int $page
     * @param int|null $totalPages
     * @return void
     */
    public static function syncForStore(Store $store, int $page = 1, ?int $totalPages = null): void
    {
        $startTime = now();
        $storeName = $store->metadata['SyncStoreName'];

        Log::channel('sync-store')->info("Processing page {$page} for store: {$store->name}", [
            'store_id' => $store->id,
            'store_name' => $store->name,
            'page' => $page,
            'started_at' => $startTime->format('Y-m-d H:i:s'),
        ]);

        $products = self::fetchProducts($storeName, $page);

        if (empty($products)) {
            Log::error("Failed to fetch products for store: {$store->name} on page: {$page}");

            Log::channel('sync-store')->error("Failed to fetch products", [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'page' => $page,
            ]);
            
            return;
        }

        if ($totalPages === null) {
            $totalPages = $products['totalPages'];
        }

        Log::info("Fetched page {$page} of products for store: {$store->name}");
        Log::info("Total Pages: {$totalPages}");

        $productsProcessed = 0;

        foreach ($products['data'] as $product) {
            Log::info("Processing product ID: " . $product['aw_product_id'] . " for store: {$store->name}");

            $savedProduct = ProductService::createOrUpdate(
                new ProductDto(
                    storeId: $store->id,
                    name: $product['product_name'],
                    description: $product['description'] ?? null,
                    price: isset($product['search_price']) ? (float) $product['search_price'] : null,
                    priceRegular: isset($product['product_price_old']) ? (float) $product['product_price_old'] : (isset($product['base_price']) ? (float) str_replace('R$ ', '', $product['base_price']) : null),
                    sku: $product['merchant_product_id'],
                    brand: $product['brand_name'] ?? null,
                    imageUrl: $product['merchant_image_url'],
                    deepLink: $product['aw_deep_link'] ?? null,
                    externalLink: $product['merchant_deep_link'] ?? null,
                    merchantProductId: $product['merchant_product_id'] ?? null,
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
                Log::info("Syncing departments for product ID: " . $savedProduct->id);
                Log::info("Department Path: " . $categoryPath);

                ProductService::syncDepartmentsFromPath(
                    $savedProduct->id,
                    $categoryPath
                );
            }

            $productsProcessed++;
        }

        $endTime = now();

        Log::channel('sync-store')->info("Completed page {$page} for store: {$store->name}", [
            'store_id' => $store->id,
            'store_name' => $store->name,
            'page' => $page,
            'total_pages' => $totalPages,
            'products_processed' => $productsProcessed,
            'started_at' => $startTime->format('Y-m-d H:i:s'),
            'finished_at' => $endTime->format('Y-m-d H:i:s'),
            'duration_seconds' => $endTime->diffInSeconds($startTime),
        ]);

        // If there are more pages, dispatch the next job
        if ($page < $totalPages) {
            SyncProductsForStoreJob::dispatch($store, $page + 1, $totalPages);
        } else {
            Log::info("Products synchronized for store: {$store->name}");
            
            Log::channel('sync-store')->info("All products synchronized for store: {$store->name}", [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'total_pages' => $totalPages,
                'finished_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Fetch products from the API.
     *
     * @param string $storeName
     * @param int $page
     * @param int $limit
     * @return array
     */
    private static function fetchProducts(string $storeName, int $page = 1, int $limit = 500): array
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