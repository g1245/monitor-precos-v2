<?php

namespace App\Services\Awin;

use App\Services\Feed\FeedManagerService;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader;

/**
 * AwinFeedListService
 *
 * Service for processing Awin feed list from remote CSV.
 * Downloads the CSV, parses it, and uses FeedManagerService to update Store and StoreFeed records.
 */
class AwinFeedListService
{
    /**
     * The URL to download the Awin feed list CSV.
     */
    private const FEED_LIST_URL = 'https://ui.awin.com/productdata-darwin-download/publisher/773041/317c85e8b0d74301ba7d7472617b6c84/1/feedList';

    /**
     * The source identifier for Awin feeds.
     */
    private const SOURCE = 'awin';

    /**
     * @var FeedManagerService
     */
    private FeedManagerService $feedManager;

    /**
     * Constructor.
     *
     * @param FeedManagerService $feedManager
     */
    public function __construct(FeedManagerService $feedManager)
    {
        $this->feedManager = $feedManager;
    }

    /**
     * Process the Awin feed list from the remote CSV.
     *
     * Downloads the CSV file, parses it, and updates/creates Store and StoreFeed records.
     *
     * @return array{success: bool, processed: int, errors: array}
     */
    public function processFeedList(): array
    {
        try {
            $csvContent = $this->downloadFeedList();
            $records = $this->parseCsv($csvContent);
            
            return $this->processRecords($records);
        } catch (\Exception $e) {
            $this->feedManager->logFeedError('sync_awin_list', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'processed' => 0,
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Download the feed list CSV from the remote URL.
     *
     * @return string The CSV content
     * @throws \Exception If download fails
     */
    private function downloadFeedList(): string
    {
        $response = Http::timeout(60)->get(self::FEED_LIST_URL);
        
        if (!$response->successful()) {
            throw new \Exception("Failed to download Awin feed list. HTTP Status: {$response->status()}");
        }
        
        return $response->body();
    }

    /**
     * Parse CSV content into an array of records.
     *
     * @param string $csvContent The CSV content to parse
     * @return array The parsed records
     * @throws \Exception If parsing fails
     */
    private function parseCsv(string $csvContent): array
    {
        $csv = Reader::createFromString($csvContent);
        $csv->setHeaderOffset(0);
        
        return iterator_to_array($csv->getRecords());
    }

    /**
     * Process each CSV record and update/create Store and StoreFeed.
     *
     * @param array $records The CSV records to process
     * @return array{success: bool, processed: int, errors: array}
     */
    private function processRecords(array $records): array
    {
        $processed = 0;
        $errors = [];
        
        foreach ($records as $index => $record) {
            try {
                $this->processRecord($record);
                $processed++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: {$e->getMessage()}";
                $this->feedManager->logFeedError('sync_awin_record', [
                    'row' => $index,
                    'record' => $record,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        $this->feedManager->logFeedAction('sync_awin_list_completed', [
            'processed' => $processed,
            'errors' => count($errors),
        ]);
        
        return [
            'success' => empty($errors),
            'processed' => $processed,
            'errors' => $errors,
        ];
    }

    /**
     * Process a single CSV record.
     *
     * Creates or updates Store and StoreFeed based on the record data.
     * Only processes records with Primary Region = BR.
     *
     * @param array $record The CSV record
     * @return void
     * @throws \Exception If required fields are missing
     */
    private function processRecord(array $record): void
    {
        $this->validateRecord($record);
        
        // Only process Brazilian stores
        if (($record['Primary Region'] ?? '') !== 'BR') {
            throw new \Exception('Record primary region is not BR');
        }
        
        // Parse last updated timestamp
        $lastUpdatedAt = null;
        if (!empty($record['Last Imported'])) {
            try {
                $lastUpdatedAt = new \DateTime($record['Last Imported']);
            } catch (\Exception $e) {
                // Ignore date parsing errors
            }
        }
        
        // Create or update store using FeedManagerService
        $store = $this->feedManager->createOrUpdateStore([
            'name' => trim($record['Advertiser Name']),
            'full_url' => '',
            'region' => $record['Primary Region'] ?? null,
            'metadata' => [
                'external_id' => $record['Advertiser ID'],
                'feed_id' => $record['Feed ID'],
                'membership_status' => $record['Membership Status'],
                'language' => $record['Language'] ?? null,
                'product_count' => (int) ($record['No of products'] ?? 0),
            ],
        ]);
        
        // Create or update store feed using FeedManagerService
        $this->feedManager->createOrUpdateStoreFeed($store->id, [
            'source' => self::SOURCE,
            'download_url' => $record['URL'],
            'last_updated_at' => $lastUpdatedAt,
        ]);
    }

    /**
     * Validate that required fields are present in the record.
     *
     * @param array $record The record to validate
     * @return void
     * @throws \Exception If validation fails
     */
    private function validateRecord(array $record): void
    {
        $requiredFields = [
            'Advertiser ID',
            'Advertiser Name',
            'Feed ID',
            'URL',
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($record[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
    }
}
