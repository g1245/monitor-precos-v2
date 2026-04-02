<?php

namespace App\Jobs\Product;

use App\Models\Store;
use App\Services\ProductSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncProductFromRabbitMQJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Number of seconds to wait before retrying the job.
     */
    public int $backoff = 10;

    /**
     * Create a new job instance.
     *
     * @param string $awProductId The AW product ID from the external API.
     * @param string $storeName   The store name as received from the queue message.
     */
    public function __construct(
        public readonly string $awProductId,
        public readonly string $storeName,
    ) {}

    /**
     * Execute the job.
     * Resolves the store by name and delegates sync to ProductSyncService.
     */
    public function handle(): void
    {
        $store = Store::where('name', $this->storeName)->first();

        if (!$store) {
            Log::warning('Store not found for RabbitMQ product sync, skipping', [
                'store_name' => $this->storeName,
                'aw_product_id' => $this->awProductId,
            ]);

            return;
        }

        $product = ProductSyncService::syncById($store, $this->awProductId);

        if (!$product) {
            Log::error('Failed to sync product from RabbitMQ message', [
                'store_name' => $this->storeName,
                'aw_product_id' => $this->awProductId,
            ]);

            $this->fail('ProductSyncService::syncById returned null.');
        }

        Log::info('Product synced successfully from RabbitMQ message', [
            'product_id' => $product->id,
            'aw_product_id' => $this->awProductId,
            'store_name' => $this->storeName,
        ]);
    }
}
