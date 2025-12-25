<?php

namespace App\Services\Awin;

use App\Services\Feed\FeedManagerService;
use App\Services\Feed\FeedStorageService;
use Illuminate\Support\Facades\Http;

/**
 * AwinFeedDownloadService
 *
 * Service for downloading Awin store feeds.
 * Finds feeds updated in the last 24 hours, downloads them,
 * saves locally using FeedStorageService, and marks them as having pending updates.
 */
class AwinFeedDownloadService
{
    /**
     * The source identifier for Awin feeds.
     */
    private const SOURCE = 'awin';

    /**
     * Download timeout in seconds.
     */
    private const DOWNLOAD_TIMEOUT = 300;

    /**
     * @var FeedManagerService
     */
    private FeedManagerService $feedManager;

    /**
     * @var FeedStorageService
     */
    private FeedStorageService $feedStorage;

    /**
     * Constructor.
     *
     * @param FeedManagerService $feedManager
     * @param FeedStorageService $feedStorage
     */
    public function __construct(
        FeedManagerService $feedManager,
        FeedStorageService $feedStorage
    ) {
        $this->feedManager = $feedManager;
        $this->feedStorage = $feedStorage;
    }

    /**
     * Process recently updated Awin feeds.
     *
     * Finds feeds updated in the last 24 hours and downloads them.
     *
     * @param int $hours Number of hours to look back (default: 24)
     * @return array{success: bool, downloaded: int, errors: array}
     */
    public function processRecentFeeds(int $hours = 24): array
    {
        $this->feedStorage->ensureDirectoryExists();
        
        $feeds = $this->feedManager->getRecentlyUpdatedFeeds($hours, self::SOURCE);
        
        if ($feeds->isEmpty()) {
            return [
                'success' => true,
                'downloaded' => 0,
                'errors' => [],
            ];
        }
        
        return $this->downloadFeeds($feeds);
    }

    /**
     * Download multiple feeds.
     *
     * @param \Illuminate\Database\Eloquent\Collection $feeds
     * @return array{success: bool, downloaded: int, errors: array}
     */
    private function downloadFeeds($feeds): array
    {
        $downloaded = 0;
        $errors = [];
        
        foreach ($feeds as $feed) {
            try {
                $this->downloadFeed($feed);
                $downloaded++;
            } catch (\Exception $e) {
                $errors[] = "Feed {$feed->id} ({$feed->store->name}): {$e->getMessage()}";
                $this->feedManager->logFeedError('download_awin_feed', [
                    'feed_id' => $feed->id,
                    'store_id' => $feed->store_id,
                    'store_name' => $feed->store->name,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        $this->feedManager->logFeedAction('download_awin_feeds_completed', [
            'downloaded' => $downloaded,
            'errors' => count($errors),
        ]);
        
        return [
            'success' => empty($errors),
            'downloaded' => $downloaded,
            'errors' => $errors,
        ];
    }

    /**
     * Download a single feed.
     *
     * Downloads the feed from the URL, saves it locally using FeedStorageService,
     * and marks it as having a pending update using FeedManagerService.
     *
     * @param \App\Models\StoreFeed $feed
     * @return void
     * @throws \Exception If download or save fails
     */
    private function downloadFeed($feed): void
    {
        $this->feedManager->logFeedAction('download_awin_feed_started', [
            'feed_id' => $feed->id,
            'store_name' => $feed->store->name,
            'url' => $feed->download_url,
        ]);
        
        // Download feed content
        $content = $this->downloadFeedContent($feed->download_url);
        
        // Save feed to disk using FeedStorageService
        $this->feedStorage->saveFeed($feed, $content);
        
        // Mark feed as having pending update using FeedManagerService
        $this->feedManager->markFeedAsPending($feed);
        
        $this->feedManager->logFeedAction('download_awin_feed_completed', [
            'feed_id' => $feed->id,
            'store_name' => $feed->store->name,
            'local_path' => $feed->getLocalFilePath(),
            'size' => strlen($content),
        ]);
    }

    /**
     * Download feed content from URL.
     *
     * Handles both regular CSV and gzipped CSV files.
     *
     * @param string $url
     * @return string The feed content
     * @throws \Exception If download fails
     */
    private function downloadFeedContent(string $url): string
    {
        $response = Http::timeout(self::DOWNLOAD_TIMEOUT)->get($url);
        
        if (!$response->successful()) {
            throw new \Exception("Failed to download Awin feed. HTTP Status: {$response->status()}");
        }
        
        $content = $response->body();
        
        // Decompress if gzipped
        if ($this->isGzipped($url)) {
            $decompressed = gzdecode($content);
            
            if ($decompressed === false) {
                throw new \Exception('Failed to decompress gzipped Awin feed');
            }
            
            $content = $decompressed;
        }
        
        return $content;
    }

    /**
     * Check if the URL points to a gzipped file.
     *
     * @param string $url
     * @return bool
     */
    private function isGzipped(string $url): bool
    {
        return str_ends_with(strtolower($url), '.gz');
    }
}
