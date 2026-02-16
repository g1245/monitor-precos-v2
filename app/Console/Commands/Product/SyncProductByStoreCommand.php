<?php

namespace App\Console\Commands\Product;

use App\Models\Store;
use Illuminate\Console\Command;

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
        $name = $this->argument('name');

        if ($name) {
            $store = Store::query()
                ->whereJsonContains('metadata->SyncStoreName', $name)
                ->first();

            if (!$store) {
                $this->error("Store not found: {$name}");
                return Command::FAILURE;
            }

            \App\Jobs\SyncProductsForStoreJob::dispatch($store);
            $this->info("Job dispatched for store: {$store->name}");
        } else {
            $stores = Store::query()
                ->whereRaw("JSON_EXTRACT(metadata, '$.SyncStoreName') IS NOT NULL")
                ->get();

            foreach ($stores as $store) {
                \App\Jobs\SyncProductsForStoreJob::dispatch($store);
                $this->info("Job dispatched for store: {$store->name}");
            }
        }

        return Command::SUCCESS;
    }
}
