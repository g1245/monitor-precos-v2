<?php

namespace App\Services\Feed;

use App\Models\StoreFeed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use MongoDB\Collection;

/**
 * MongoProductImportService
 *
 * Service for importing feed products into MongoDB.
 * Handles CSV parsing, data transformation, and MongoDB upsert operations.
 * Maintains price history by appending new price information to existing products.
 */
class MongoProductImportService
{
    /**
     * MongoDB collection name for products.
     */
    private const COLLECTION_NAME = 'products';

    /**
     * Unique identifier field for products.
     */
    private const UNIQUE_FIELD = 'merchant_product_id';

    /**
     * Price fields to track in history.
     */
    private const PRICE_FIELDS = [
        'rrp_price',
        'search_price',
        'base_price',
        'base_price_text',
        'store_price',
        'display_price',
        'product_price_old',
    ];

    /**
     * Fields that need column name normalization.
     */
    private const COLUMN_MAPPING = [
        'Fashion:suitable_for' => 'fashion_suitable_for',
        'Fashion:category' => 'fashion_category',
        'Fashion:size' => 'fashion_size',
        'Fashion:material' => 'fashion_material',
        'Fashion:pattern' => 'fashion_pattern',
        'Fashion:swatch' => 'fashion_swatch',
        'GroupBuying:event_date' => 'groupbuying_event_date',
        'GroupBuying:expiry_date' => 'groupbuying_expiry_date',
        'GroupBuying:expiry_time' => 'groupbuying_expiry_time',
        'GroupBuying:event_city' => 'groupbuying_event_city',
        'GroupBuying:event_address' => 'groupbuying_event_address',
        'GroupBuying:number_sessions' => 'groupbuying_number_sessions',
        'GroupBuying:terms' => 'groupbuying_terms',
        'GroupBuying:number_sold' => 'groupbuying_number_sold',
        'GroupBuying:min_required' => 'groupbuying_min_required',
        'GroupBuying:supplier' => 'groupbuying_supplier',
        'GroupBuying:group_latitude' => 'groupbuying_group_latitude',
        'GroupBuying:group_longitude' => 'groupbuying_group_longitude',
        'GroupBuying:deal_start' => 'groupbuying_deal_start',
        'GroupBuying:deal_end' => 'groupbuying_deal_end',
        'ShoppingNL:energy_label' => 'shoppingnl_energy_label',
        'ShoppingNL:energy_label_link' => 'shoppingnl_energy_label_link',
        'ShoppingNL:energy_label_logo' => 'shoppingnl_energy_label_logo',
        'ShoppingNL:google_taxonomy' => 'shoppingnl_google_taxonomy',
    ];

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var FeedStorageService
     */
    private FeedStorageService $feedStorage;

    /**
     * Constructor.
     *
     * @param FeedStorageService $feedStorage
     */
    public function __construct(FeedStorageService $feedStorage)
    {
        $this->feedStorage = $feedStorage;
        $this->collection = $this->getMongoCollection();
        $this->ensureIndexes();
    }

    /**
     * Import products from a feed CSV file into MongoDB.
     *
     * @param StoreFeed $feed
     * @return array{processed: int, skipped: int, errors: array}
     */
    public function importFeedProducts(StoreFeed $feed): array
    {
        $filePath = $this->feedStorage->getFeedPath($feed);

        if (!file_exists($filePath)) {
            throw new \Exception("Feed file not found: {$filePath}");
        }

        Log::info('Starting MongoDB product import', [
            'feed_id' => $feed->id,
            'store_name' => $feed->store->name,
            'file_path' => $filePath,
        ]);

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        $processed = 0;
        $skipped = 0;
        $errors = [];

        foreach ($csv->getRecords() as $index => $record) {
            try {
                // Normalize column names
                $normalizedRecord = $this->normalizeColumnNames($record);

                // Skip if no merchant_product_id
                if (empty($normalizedRecord[self::UNIQUE_FIELD])) {
                    $skipped++;
                    Log::debug('Skipped record without merchant_product_id', [
                        'feed_id' => $feed->id,
                        'row' => $index + 1,
                        'aw_product_id' => $normalizedRecord['aw_product_id'] ?? null,
                    ]);
                    continue;
                }

                // Process the product
                $this->upsertProduct($normalizedRecord, $feed);
                $processed++;

                // Log progress every 100 records
                if (($processed + $skipped) % 100 === 0) {
                    Log::info('Import progress', [
                        'feed_id' => $feed->id,
                        'processed' => $processed,
                        'skipped' => $skipped,
                    ]);
                }
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Row " . ($index + 1) . ": {$e->getMessage()}";
                Log::error('Error importing product', [
                    'feed_id' => $feed->id,
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('MongoDB product import completed', [
            'feed_id' => $feed->id,
            'store_name' => $feed->store->name,
            'processed' => $processed,
            'skipped' => $skipped,
            'errors' => count($errors),
        ]);

        return [
            'processed' => $processed,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Import a batch of products from a feed CSV file into MongoDB.
     * Processes only records within the specified offset and limit range.
     *
     * @param StoreFeed $feed
     * @param int $offset Starting offset (0-based)
     * @param int $limit Number of records to process
     * @return array{processed: int, skipped: int, errors: array}
     */
    public function importFeedProductsBatch(StoreFeed $feed, int $offset, int $limit): array
    {
        $filePath = $this->feedStorage->getFeedPath($feed);

        if (!file_exists($filePath)) {
            throw new \Exception("Feed file not found: {$filePath}");
        }

        Log::debug('Starting MongoDB batch import', [
            'feed_id' => $feed->id,
            'store_name' => $feed->store->name,
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        $processed = 0;
        $skipped = 0;
        $errors = [];
        $currentIndex = 0;

        foreach ($csv->getRecords() as $index => $record) {
            // Skip records before offset
            if ($currentIndex < $offset) {
                $currentIndex++;
                continue;
            }

            // Stop when we've processed enough records
            if ($processed + $skipped >= $limit) {
                break;
            }

            try {
                // Normalize column names
                $normalizedRecord = $this->normalizeColumnNames($record);

                // Skip if no merchant_product_id
                if (empty($normalizedRecord[self::UNIQUE_FIELD])) {
                    $skipped++;
                    Log::debug('Skipped record without merchant_product_id', [
                        'feed_id' => $feed->id,
                        'row' => $index + 1,
                        'aw_product_id' => $normalizedRecord['aw_product_id'] ?? null,
                    ]);
                    continue;
                }

                // Process the product
                $this->upsertProduct($normalizedRecord, $feed);
                $processed++;
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Row " . ($index + 1) . ": {$e->getMessage()}";
                Log::error('Error importing product in batch', [
                    'feed_id' => $feed->id,
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                ]);
            }

            $currentIndex++;
        }

        Log::debug('MongoDB batch import completed', [
            'feed_id' => $feed->id,
            'store_name' => $feed->store->name,
            'offset' => $offset,
            'limit' => $limit,
            'processed' => $processed,
            'skipped' => $skipped,
            'errors' => count($errors),
        ]);

        return [
            'processed' => $processed,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Upsert a product into MongoDB.
     * If product exists, update it and append price info to prices array.
     * If new, create it with initial price info.
     *
     * @param array $record
     * @param StoreFeed $feed
     * @return void
     */
    private function upsertProduct(array $record, StoreFeed $feed): void
    {
        $merchantProductId = $record[self::UNIQUE_FIELD];

        // Build price information
        $priceInfo = [
            'timestamp' => new \MongoDB\BSON\UTCDateTime(),
            'aw_product_id' => $record['aw_product_id'] ?? null,
            'feed_id' => $feed->id,
            'store_id' => $feed->store_id,
        ];

        // Add price fields if they exist and are not empty
        foreach (self::PRICE_FIELDS as $field) {
            if (!empty($record[$field])) {
                $priceInfo[$field] = $record[$field];
            }
        }

        // Check if product exists
        $existingProduct = $this->collection->findOne([
            self::UNIQUE_FIELD => $merchantProductId,
        ]);

        if ($existingProduct) {
            // Update existing product and add new price to history
            $this->collection->updateOne(
                [self::UNIQUE_FIELD => $merchantProductId],
                [
                    '$set' => $record,
                    '$push' => ['prices' => $priceInfo],
                ]
            );
        } else {
            // Insert new product with initial price history
            $record['prices'] = [$priceInfo];
            $record['created_at'] = new \MongoDB\BSON\UTCDateTime();
            $this->collection->insertOne($record);
        }
    }

    /**
     * Normalize column names from CSV headers.
     *
     * @param array $record
     * @return array
     */
    private function normalizeColumnNames(array $record): array
    {
        $normalized = [];

        foreach ($record as $key => $value) {
            $normalizedKey = self::COLUMN_MAPPING[$key] ?? $key;
            $normalized[$normalizedKey] = $value;
        }

        return $normalized;
    }

    /**
     * Get MongoDB collection instance.
     *
     * @return Collection
     */
    private function getMongoCollection(): Collection
    {
        $connection = DB::connection('mongodb');
        $database = $connection->getMongoDB();

        return $database->selectCollection(self::COLLECTION_NAME);
    }

    /**
     * Ensure MongoDB indexes are created.
     *
     * @return void
     */
    private function ensureIndexes(): void
    {
        try {
            // Create unique index on merchant_product_id
            $this->collection->createIndex(
                [self::UNIQUE_FIELD => 1],
                ['unique' => true]
            );

            Log::debug('MongoDB indexes ensured', [
                'collection' => self::COLLECTION_NAME,
                'unique_field' => self::UNIQUE_FIELD,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to ensure MongoDB indexes', [
                'collection' => self::COLLECTION_NAME,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
