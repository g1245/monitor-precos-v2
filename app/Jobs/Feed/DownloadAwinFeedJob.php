<?php

namespace App\Jobs\Feed;

use App\Models\StoreFeed;
use App\Services\Feed\FeedManagerService;
use App\Services\Feed\FeedStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * DownloadAwinFeedJob
 *
 * Job to download, extract, and process a single Awin feed.
 * Handles verification if file needs to be downloaded, extracts .gz files,
 * and marks feed as pending update.
 */
class DownloadAwinFeedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Download timeout in seconds.
     */
    private const DOWNLOAD_TIMEOUT = 300;

    /**
     * The storage disk for feeds.
     */
    private const STORAGE_DISK = 'local';

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
    public $timeout = 360;

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
     * @param FeedManagerService $feedManager
     * @param FeedStorageService $feedStorage
     * @return void
     */
    public function handle(
        FeedManagerService $feedManager,
        FeedStorageService $feedStorage
    ): void {
        $feedManager->logFeedAction('download_awin_feed_started', [
            'feed_id' => $this->feed->id,
            'store_name' => $this->feed->store->name,
            'url' => $this->feed->download_url,
        ]);

        try {
            // Check if we need to download (feed not exists or has update)
            if (!$this->shouldDownload($feedStorage)) {
                $feedManager->logFeedAction('download_awin_feed_skipped', [
                    'feed_id' => $this->feed->id,
                    'store_name' => $this->feed->store->name,
                    'reason' => 'File already exists and no update pending',
                ]);
                return;
            }

            $feedStorage->ensureDirectoryExists();

            // Download .gz file
            $gzPath = $this->downloadGzFile();

            // Extract .gz to .csv
            $csvContent = $this->extractGzFile($gzPath);

            // Save CSV content
            $feedStorage->saveFeed($this->feed, $csvContent);

            // Delete .gz file
            Storage::disk(self::STORAGE_DISK)->delete($gzPath);

            // Mark feed as having pending update
            $feedManager->markFeedAsPending($this->feed);

            $feedManager->logFeedAction('download_awin_feed_completed', [
                'feed_id' => $this->feed->id,
                'store_name' => $this->feed->store->name,
                'local_path' => $this->feed->getLocalFilePath(),
                'size' => strlen($csvContent),
            ]);
        } catch (\Exception $e) {
            $feedManager->logFeedError('download_awin_feed', [
                'feed_id' => $this->feed->id,
                'store_id' => $this->feed->store_id,
                'store_name' => $this->feed->store->name,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Check if feed should be downloaded.
     *
     * Downloads if file doesn't exist locally or if last_updated_at is recent.
     *
     * @param FeedStorageService $feedStorage
     * @return bool
     */
    private function shouldDownload(FeedStorageService $feedStorage): bool
    {
        // Always download if file doesn't exist
        if (!$feedStorage->feedExists($this->feed)) {
            return true;
        }

        // Download if last_updated_at is within last 24 hours
        $recentCutoff = now()->subHours(24);
        if ($this->feed->last_updated_at && $this->feed->last_updated_at->gte($recentCutoff)) {
            return true;
        }

        return false;
    }

    /**
     * Download .gz file from Awin.
     *
     * @return string The temporary path to the .gz file
     * @throws \Exception If download fails
     */
    private function downloadGzFile(): string
    {
        $response = Http::timeout(self::DOWNLOAD_TIMEOUT)->get($this->feed->download_url);

        if (!$response->successful()) {
            throw new \Exception("Failed to download Awin feed. HTTP Status: {$response->status()}");
        }

        // Save .gz file temporarily
        $gzPath = "store_feeds/temp_feed_{$this->feed->id}.gz";
        Storage::disk(self::STORAGE_DISK)->put($gzPath, $response->body());

        return $gzPath;
    }

    /**
     * Extract .gz file to get CSV content.
     *
     * @param string $gzPath Path to the .gz file
     * @return string The extracted CSV content
     * @throws \Exception If extraction fails
     */
    private function extractGzFile(string $gzPath): string
    {
        $gzFullPath = Storage::disk(self::STORAGE_DISK)->path($gzPath);

        // Read and decompress
        $gzContent = file_get_contents($gzFullPath);
        $csvContent = gzdecode($gzContent);

        if ($csvContent === false) {
            throw new \Exception('Failed to extract gzipped Awin feed');
        }

        return $csvContent;
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        app(FeedManagerService::class)->logFeedError('download_awin_feed_failed', [
            'feed_id' => $this->feed->id,
            'store_id' => $this->feed->store_id,
            'store_name' => $this->feed->store->name,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);
    }
}
