<?php

namespace App\Console\Commands\Product;

use Illuminate\Console\Command;

class CreateTodayPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-today-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily price history entries for all active products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = \App\Models\Product::active()->get();

        $this->info('Starting daily price history creation for ' . $products->count() . ' active products.');

        $createdCount = 0;

        foreach ($products as $product) {
            if ($product->shouldRecordPriceHistory()) {
                $product->addPriceHistory($product->price);
                $createdCount++;
            }
        }

        $this->info("Daily price history creation completed. Created {$createdCount} new entries.");
    }
}
