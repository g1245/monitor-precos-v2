<?php

namespace App\Services;

use App\Dto\ProductAttributeDto;
use App\Jobs\Product\SyncProductsForStoreJob;
use App\Models\Product;
use App\Models\Store;
use App\Services\ProductAttributeService;
use App\Services\ProductDtoResolver;
use App\Services\ProductService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductSyncService
{
    /**
     * Sync all products for a given store, paginating through the API.
     *
     * @param Store $store
     * @param int $page
     * @param int|null $totalPages
     * @param string|null $updatedAtFrom
     * @return void
     */
    public static function syncForStore(Store $store, int $page = 1, ?int $totalPages = null, ?string $updatedAtFrom = null): void
    {
        $startTime = now();
        $storeName = $store->metadata['SyncStoreName'];

        Log::channel('sync-store')->info("Processing page {$page} for store: {$store->name}", [
            'store_id' => $store->id,
            'store_name' => $store->name,
            'page' => $page,
            'updated_at_from' => $updatedAtFrom,
            'started_at' => $startTime->format('Y-m-d H:i:s'),
        ]);

        $response = self::fetchProducts($storeName, $page, 500, $updatedAtFrom);

        if (empty($response)) {
            Log::error("Failed to fetch products for store: {$store->name} on page: {$page}");

            Log::channel('sync-store')->error("Failed to fetch products", [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'page' => $page,
                'updated_at_from' => $updatedAtFrom,
            ]);

            return;
        }

        if ($totalPages === null) {
            $totalPages = $response['totalPages'];
        }

        Log::info("Fetched page {$page} of products for store: {$store->name}");
        Log::info("Total Pages: {$totalPages}");

        $productsProcessed = 0;
        $dtoClass = ProductDtoResolver::resolve($store);

        foreach ($response['data'] as $product) {
            if (self::processProductData($store, $product, $dtoClass)) {
                $productsProcessed++;
            }
        }

        $endTime = now();

        Log::channel('sync-store')->info("Completed page {$page} for store: {$store->name}", [
            'store_id' => $store->id,
            'store_name' => $store->name,
            'page' => $page,
            'total_pages' => $totalPages,
            'updated_at_from' => $updatedAtFrom,
            'products_processed' => $productsProcessed,
            'started_at' => $startTime->format('Y-m-d H:i:s'),
            'finished_at' => $endTime->format('Y-m-d H:i:s'),
            'duration_seconds' => $endTime->diffInSeconds($startTime),
        ]);

        if ($page < $totalPages) {
            SyncProductsForStoreJob::dispatch($store, $page + 1, $totalPages, $updatedAtFrom);
        } else {
            Log::info("Products synchronized for store: {$store->name}");

            Log::channel('sync-store')->info("All products synchronized for store: {$store->name}", [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'total_pages' => $totalPages,
                'updated_at_from' => $updatedAtFrom,
                'finished_at' => now()->format('Y-m-d H:i:s'),
            ]);

            self::flushWelcomeCache();
        }
    }

    /**
     * Sync a single product by its AW product ID.
     * Fetches the product directly from the API and persists it using the store's DTO.
     *
     * @param Store $store
     * @param string $awProductId
     * @return Product|null Returns the saved product, or null on failure.
     */
    public static function syncById(Store $store, string $awProductId): ?Product
    {
        Log::info("Syncing individual product", [
            'store_id' => $store->id,
            'store_name' => $store->name,
            'aw_product_id' => $awProductId,
        ]);

        $product = self::fetchProductById($awProductId);

        if (empty($product)) {
            Log::error("Failed to fetch product by ID", [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'aw_product_id' => $awProductId,
            ]);

            return null;
        }

        $dtoClass = ProductDtoResolver::resolve($store);

        return self::processProductData($store, $product, $dtoClass)
            ? Product::where('store_id', $store->id)
                ->where('sku', $product['merchant_product_id'] ?? null)
                ->first()
            : null;
    }

    /**
     * Process and persist a single raw product array from the API.
     * Handles price validation, upsert, price history, attributes and departments.
     * Used by both batch (syncForStore) and individual (syncById) flows.
     *
     * @param Store $store
     * @param array $product Raw product data from the API.
     * @param class-string $dtoClass Resolved DTO class for this store.
     * @return bool True if the product was processed successfully, false otherwise.
     */
    public static function processProductData(Store $store, array $product, string $dtoClass): bool
    {
        Log::info("Processing product", [
            'merchant_product_id' => $product['merchant_product_id'] ?? 'N/A',
            'aw_product_id' => $product['aw_product_id'] ?? 'N/A',
            'store_name' => $store->name,
        ]);

        $priceData = $product['price'] ?? [];

        if (!$dtoClass::hasValidPrices($priceData)) {
            Log::error("Missing price fields for product, skipping", [
                'store_name' => $store->name,
                'sku' => $product['merchant_product_id'] ?? 'N/A',
                'price' => $priceData,
            ]);

            return false;
        }

        try {
            $savedProduct = ProductService::createOrUpdate(
                $dtoClass::fromApiData($store->id, $product)
            );
        } catch (\Throwable $e) {
            Log::error("Failed to create or update product", [
                'merchant_product_id' => $product['merchant_product_id'] ?? 'N/A',
                'aw_product_id' => $product['aw_product_id'] ?? 'N/A',
                'store_name' => $store->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }

        if ($savedProduct->shouldRecordPriceHistory()) {
            $savedProduct->addPriceHistory($savedProduct->price);
        }

        ProductAttributeService::sync(
            ProductAttributeDto::fromApiData($savedProduct->id, $product)
        );

        $categoryPath = $product['merchant_category'] ?? $product['merchant_product_category_path'] ?? null;

        if ($categoryPath) {
            Log::info("Syncing departments for product ID: {$savedProduct->id}", [
                'department_path' => $categoryPath,
            ]);

            ProductService::syncDepartmentsFromPath($savedProduct->id, $categoryPath);
        }

        return true;
    }

    /**
     * Flush all per-tab welcome page cache entries.
     * Called once when the full store sync is complete.
     */
    private static function flushWelcomeCache(): void
    {
        Cache::forget('welcome_products:destaques');
        Cache::forget('welcome_products:recentes');
        Cache::forget('welcome_products:mais-acessados');
    }

    /**
     * Fetch a paginated list of products from the API for a given store.
     *
     * @param string $storeName
     * @param int $page
     * @param int $limit
     * @param string|null $updatedAtFrom
     * @return array
     */
    private static function fetchProducts(string $storeName, int $page = 1, int $limit = 500, ?string $updatedAtFrom = null): array
    {
        $query = [
            'merchant_name' => $storeName,
            'page' => $page,
            'limit' => $limit,
        ];

        if ($updatedAtFrom !== null) {
            $query['updated_at_from'] = $updatedAtFrom;
        }

        Log::info("Fetching products from API for store: {$storeName}", [
            'query' => $query,
            'endpoint' => config('services.awin.url') . '/products',
        ]);

        $response = Http::withHeaders([
            'x-api-key' => config('services.awin.token'),
        ])->get(config('services.awin.url') . '/products', $query);

        if ($response->failed()) {
            return [];
        }

        return $response->json();
    }

    /**
     * Fetch a single product by its AW product ID from the API.
     *
     * @param string $awProductId
     * @return array
     */
    private static function fetchProductById(string $awProductId): array
    {
        $endpoint = config('services.awin.url') . '/products/' . $awProductId;

        Log::info("Fetching product by ID from API", [
            'aw_product_id' => $awProductId,
            'endpoint' => $endpoint,
        ]);

        $response = Http::withHeaders([
            'x-api-key' => config('services.awin.token'),
        ])->get($endpoint);

        if ($response->failed()) {
            Log::error("API request failed for product ID: {$awProductId}", [
                'status' => $response->status(),
            ]);

            return [];
        }

        return $response->json() ?? [];
    }
}