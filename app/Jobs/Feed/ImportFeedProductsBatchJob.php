<?php

namespace App\Jobs\Feed;

use App\Models\StoreFeed;
use App\Services\Feed\MongoProductImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * ImportFeedProductsBatchJob
 *
 * Job to import a batch of products from a feed CSV into MongoDB.
 * Processes a specific range of lines from the feed file.
 */
class ImportFeedProductsBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * @var StoreFeed
     */
    private StoreFeed $feed;

    /**
     * @var int
     */
    private int $offset;

    /**
     * @var int
     */
    private int $limit;

    /**
     * @var int
     */
    private int $batchNumber;

    /**
     * @var int
     */
    private int $totalBatches;

    /**
     * Create a new job instance.
     *
     * @param StoreFeed $feed
     * @param int $offset Starting offset (0-based)
     * @param int $limit Number of records to process
     * @param int $batchNumber Current batch number
     * @param int $totalBatches Total number of batches
     */
    public function __construct(
        StoreFeed $feed,
        int $offset,
        int $limit,
        int $batchNumber,
        int $totalBatches
    ) {
        $this->feed = $feed;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->batchNumber = $batchNumber;
        $this->totalBatches = $totalBatches;
    }

    /**
     * Execute the job.
     *
     * @param MongoProductImportService $mongoImport
     * @return void
     */
    public function handle(MongoProductImportService $mongoImport): void
    {
        Log::info('Starting batch import', [
            'feed_id' => $this->feed->id,
            'store_name' => $this->feed->store->name,
            'batch' => $this->batchNumber,
            'total_batches' => $this->totalBatches,
            'offset' => $this->offset,
            'limit' => $this->limit,
        ]);

        try {
            $result = $mongoImport->importFeedProductsBatch(
                $this->feed,
                $this->offset,
                $this->limit
            );

            Log::info('Batch import completed', [
                'feed_id' => $this->feed->id,
                'store_name' => $this->feed->store->name,
                'batch' => $this->batchNumber,
                'total_batches' => $this->totalBatches,
                'processed' => $result['processed'],
                'skipped' => $result['skipped'],
                'errors' => count($result['errors']),
            ]);
        } catch (\Exception $e) {
            Log::error('Batch import failed', [
                'feed_id' => $this->feed->id,
                'store_name' => $this->feed->store->name,
                'batch' => $this->batchNumber,
                'total_batches' => $this->totalBatches,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
