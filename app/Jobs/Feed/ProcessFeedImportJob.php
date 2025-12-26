<?php

namespace App\Jobs\Feed;

use App\Models\StoreFeed;
use App\Services\Feed\FeedStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

/**
 * ProcessFeedImportJob
 *
 * Job to orchestrate the import of a feed by dividing it into batches
 * and dispatching multiple ImportFeedProductsBatchJob instances.
 * Each batch processes up to 1000 records in parallel.
 */
class ProcessFeedImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Batch size for processing.
     */
    private const BATCH_SIZE = 1000;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * @var StoreFeed
     */
    private StoreFeed $feed;

    /**
     * Create a new job instance.
     *
     * @param StoreFeed $feed
     */
    public function __construct(StoreFeed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * Execute the job.
     *
     * @param FeedStorageService $feedStorage
     * @return void
     */
    public function handle(FeedStorageService $feedStorage): void
    {
        Log::info('Starting feed import orchestration', [
            'feed_id' => $this->feed->id,
            'store_name' => $this->feed->store->name,
        ]);

        try {
            $filePath = $feedStorage->getFeedPath($this->feed);

            if (!file_exists($filePath)) {
                throw new \Exception("Feed file not found: {$filePath}");
            }

            // Count total records in the CSV
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
            $totalRecords = count($csv);

            // Calculate number of batches
            $totalBatches = (int) ceil($totalRecords / self::BATCH_SIZE);

            Log::info('Feed import batches prepared', [
                'feed_id' => $this->feed->id,
                'store_name' => $this->feed->store->name,
                'total_records' => $totalRecords,
                'batch_size' => self::BATCH_SIZE,
                'total_batches' => $totalBatches,
            ]);

            // Dispatch batch jobs
            for ($i = 0; $i < $totalBatches; $i++) {
                $offset = $i * self::BATCH_SIZE;
                $batchNumber = $i + 1;

                ImportFeedProductsBatchJob::dispatch(
                    $this->feed,
                    $offset,
                    self::BATCH_SIZE,
                    $batchNumber,
                    $totalBatches
                );

                Log::debug('Dispatched batch job', [
                    'feed_id' => $this->feed->id,
                    'batch' => $batchNumber,
                    'total_batches' => $totalBatches,
                    'offset' => $offset,
                ]);
            }

            Log::info('All batch jobs dispatched successfully', [
                'feed_id' => $this->feed->id,
                'store_name' => $this->feed->store->name,
                'total_batches' => $totalBatches,
            ]);
        } catch (\Exception $e) {
            Log::error('Feed import orchestration failed', [
                'feed_id' => $this->feed->id,
                'store_name' => $this->feed->store->name,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
