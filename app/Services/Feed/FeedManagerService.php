<?php

namespace App\Services\Feed;

use App\Models\Store;
use App\Models\StoreFeed;
use Illuminate\Support\Facades\Log;

/**
 * FeedManagerService
 *
 * Generic service for managing Store and StoreFeed records.
 * Used by affiliate platform packages to store feed data.
 */
class FeedManagerService
{
    /**
     * Create or update a Store record.
     *
     * @param array $data Store data
     * @return Store
     */
    public function createOrUpdateStore(array $data): Store
    {
        $uniqueFields = ['name' => $data['name']];
        
        $fillableFields = array_filter($data, function ($key) {
            return in_array($key, ['name', 'logo', 'region', 'full_url', 'metadata']);
        }, ARRAY_FILTER_USE_KEY);
        
        return Store::updateOrCreate($uniqueFields, $fillableFields);
    }

    /**
     * Create or update a StoreFeed record.
     *
     * @param int $storeId Store ID
     * @param array $data StoreFeed data
     * @return StoreFeed
     */
    public function createOrUpdateStoreFeed(int $storeId, array $data): StoreFeed
    {
        $uniqueFields = [
            'store_id' => $storeId,
            'download_url' => $data['download_url'],
        ];
        
        $fillableFields = array_filter($data, function ($key) {
            return in_array($key, ['source', 'download_url', 'last_updated_at']);
        }, ARRAY_FILTER_USE_KEY);
        
        return StoreFeed::updateOrCreate($uniqueFields, $fillableFields);
    }

    /**
     * Mark a feed as having a pending update.
     *
     * @param StoreFeed $feed
     * @return bool
     */
    public function markFeedAsPending(StoreFeed $feed): bool
    {
        return $feed->update(['has_pending_update' => true]);
    }

    /**
     * Mark a feed as processed (no pending update).
     *
     * @param StoreFeed $feed
     * @return bool
     */
    public function markFeedAsProcessed(StoreFeed $feed): bool
    {
        return $feed->update(['has_pending_update' => false]);
    }

    /**
     * Get feeds with pending updates.
     *
     * @param string|null $source Filter by source
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingFeeds(?string $source = null)
    {
        $query = StoreFeed::with('store')
            ->where('has_pending_update', true);
        
        if ($source) {
            $query->where('source', $source);
        }
        
        return $query->get();
    }

    /**
     * Get recently updated feeds.
     *
     * @param int $hours Number of hours to look back
     * @param string|null $source Filter by source
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentlyUpdatedFeeds(int $hours = 24, ?string $source = null)
    {
        $cutoff = now()->subHours($hours);
        
        $query = StoreFeed::with('store')
            ->where('last_updated_at', '>=', $cutoff)
            ->whereNotNull('download_url');
        
        if ($source) {
            $query->where('source', $source);
        }
        
        return $query->get();
    }

    /**
     * Delete a store if it has no feeds.
     *
     * @param Store $store
     * @return bool
     */
    public function deleteStoreIfEmpty(Store $store): bool
    {
        if ($store->storeFeeds()->count() === 0) {
            return $store->delete();
        }
        
        return false;
    }

    /**
     * Log feed processing result.
     *
     * @param string $action
     * @param array $context
     * @return void
     */
    public function logFeedAction(string $action, array $context): void
    {
        Log::info("Feed {$action}", $context);
    }

    /**
     * Log feed error.
     *
     * @param string $action
     * @param array $context
     * @return void
     */
    public function logFeedError(string $action, array $context): void
    {
        Log::error("Feed {$action} failed", $context);
    }
}
