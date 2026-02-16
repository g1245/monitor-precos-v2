<?php

namespace App\Services;

use App\Models\Store;
use App\Dto\ProductDto;
use App\Dto\ProductAttributeDto;
use App\Services\ProductService;
use App\Services\ProductAttributeService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductSyncService
{
    /**
     * Sync products for a given store.
     *
     * @param Store $store
     * @return void
     */
    public static function syncForStore(Store $store): void
    {
        $storeName = $store->metadata['SyncStoreName'];

        $page = 1;
        $totalPages = 0;

        do {
            $products = self::fetchProducts($storeName, $page++);

            if (empty($products)) {
                Log::error("Failed to fetch products for store: {$store->name} on page: {$page}");
                return;
            }

            $totalPages = $products['totalPages'];

            Log::info("Fetched page {$page} of products for store: {$store->name}");
            Log::info("Total Pages: {$totalPages}");

            foreach ($products['data'] as $product) {
                Log::info("Processing product ID: " . $product['aw_product_id'] . " for store: {$store->name}");

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
                    Log::info("Syncing departments for product ID: " . $savedProduct->id);
                    Log::info("Department Path: " . $categoryPath);

                    ProductService::syncDepartmentsFromPath(
                        $savedProduct->id,
                        $categoryPath
                    );
                }
            }
        } while ($page < $totalPages);

        Log::info("Products synchronized for store: {$store->name}");
    }

    /**
     * Fetch products from the API.
     *
     * @param string $storeName
     * @param int $page
     * @param int $limit
     * @return array
     */
    private static function fetchProducts(string $storeName, int $page = 1, int $limit = 100): array
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