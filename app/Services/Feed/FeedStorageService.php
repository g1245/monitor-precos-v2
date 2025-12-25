<?php

namespace App\Services\Feed;

use App\Models\StoreFeed;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * FeedStorageService
 *
 * Generic service for managing feed file storage.
 * Handles saving, reading, and deleting feed files.
 */
class FeedStorageService
{
    /**
     * The disk where feeds will be stored.
     */
    private const STORAGE_DISK = 'local';

    /**
     * The directory path for storing feeds.
     */
    private const FEEDS_DIRECTORY = 'store_feeds';

    /**
     * Save feed content to disk.
     *
     * @param StoreFeed $feed
     * @param string $content
     * @return bool
     * @throws \Exception If save fails
     */
    public function saveFeed(StoreFeed $feed, string $content): bool
    {
        $path = $feed->getLocalFilePath();
        
        $saved = Storage::disk(self::STORAGE_DISK)->put($path, $content);
        
        if (!$saved) {
            throw new \Exception('Failed to save feed to disk');
        }
        
        Log::info('Feed saved to disk', [
            'feed_id' => $feed->id,
            'path' => $path,
            'size' => strlen($content),
        ]);
        
        return true;
    }

    /**
     * Check if a feed file exists locally.
     *
     * @param StoreFeed $feed
     * @return bool
     */
    public function feedExists(StoreFeed $feed): bool
    {
        return Storage::disk(self::STORAGE_DISK)->exists($feed->getLocalFilePath());
    }

    /**
     * Get the full file path for a feed.
     *
     * @param StoreFeed $feed
     * @return string
     */
    public function getFeedPath(StoreFeed $feed): string
    {
        return $feed->getFullLocalFilePath();
    }

    /**
     * Read feed content from disk.
     *
     * @param StoreFeed $feed
     * @return string|null
     */
    public function readFeed(StoreFeed $feed): ?string
    {
        if (!$this->feedExists($feed)) {
            return null;
        }
        
        return Storage::disk(self::STORAGE_DISK)->get($feed->getLocalFilePath());
    }

    /**
     * Delete a feed file from local storage.
     *
     * @param StoreFeed $feed
     * @return bool
     */
    public function deleteFeed(StoreFeed $feed): bool
    {
        if (!$this->feedExists($feed)) {
            return false;
        }
        
        $deleted = Storage::disk(self::STORAGE_DISK)->delete($feed->getLocalFilePath());
        
        if ($deleted) {
            Log::info('Feed deleted from disk', [
                'feed_id' => $feed->id,
                'path' => $feed->getLocalFilePath(),
            ]);
        }
        
        return $deleted;
    }

    /**
     * Get the size of a feed file in bytes.
     *
     * @param StoreFeed $feed
     * @return int|null
     */
    public function getFeedSize(StoreFeed $feed): ?int
    {
        if (!$this->feedExists($feed)) {
            return null;
        }
        
        return Storage::disk(self::STORAGE_DISK)->size($feed->getLocalFilePath());
    }

    /**
     * Get the last modified time of a feed file.
     *
     * @param StoreFeed $feed
     * @return int|null Unix timestamp
     */
    public function getFeedLastModified(StoreFeed $feed): ?int
    {
        if (!$this->feedExists($feed)) {
            return null;
        }
        
        return Storage::disk(self::STORAGE_DISK)->lastModified($feed->getLocalFilePath());
    }

    /**
     * Create the feeds directory if it doesn't exist.
     *
     * @return bool
     */
    public function ensureDirectoryExists(): bool
    {
        if (!Storage::disk(self::STORAGE_DISK)->exists(self::FEEDS_DIRECTORY)) {
            return Storage::disk(self::STORAGE_DISK)->makeDirectory(self::FEEDS_DIRECTORY);
        }
        
        return true;
    }

    /**
     * Delete all feeds for a specific source.
     *
     * @param string $source
     * @return int Number of files deleted
     */
    public function deleteAllFeedsBySource(string $source): int
    {
        $feeds = StoreFeed::where('source', $source)->get();
        $deleted = 0;
        
        foreach ($feeds as $feed) {
            if ($this->deleteFeed($feed)) {
                $deleted++;
            }
        }
        
        return $deleted;
    }
}
