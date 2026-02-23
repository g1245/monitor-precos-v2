<?php

namespace App\Console\Commands\Product;

use App\Jobs\Product\SyncProductsForStoreJob;
use App\Models\Store;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncProductByStoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-product-by-store {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize products by store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = now();
        $name = $this->argument('name');

        Log::channel('sync-store')->info('Sync process started', [
            'started_at' => $startTime->format('Y-m-d H:i:s'),
            'store_filter' => $name ?? 'all stores',
        ]);

        if ($name) {
            $store = Store::query()
                ->whereJsonContains('metadata->SyncStoreName', $name)
                ->first();

            if (!$store) {
                $this->error("Store not found: {$name}");

                Log::channel('sync-store')->error('Store not found', [
                    'store_name' => $name,
                    'started_at' => $startTime->format('Y-m-d H:i:s'),
                    'finished_at' => now()->format('Y-m-d H:i:s'),
                ]);
                
                return Command::FAILURE;
            }

            SyncProductsForStoreJob::dispatch($store);

            $this->info("Job dispatched for store: {$store->name}");
        } else {
            $stores = Store::query()
                ->whereRaw("JSON_EXTRACT(metadata, '$.SyncStoreName') IS NOT NULL")
                ->get();

            foreach ($stores as $store) {
                SyncProductsForStoreJob::dispatch($store);

                $this->info("Job dispatched for store: {$store->name}");
            }
        }

        $endTime = now();

        Log::channel('sync-store')->info('Sync jobs dispatched', [
            'started_at' => $startTime->format('Y-m-d H:i:s'),
            'finished_at' => $endTime->format('Y-m-d H:i:s'),
            'duration_seconds' => $endTime->diffInSeconds($startTime),
        ]);

        return Command::SUCCESS;
    }
}
