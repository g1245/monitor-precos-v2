<?php

namespace App\Observers;

use App\Jobs\SendPriceAlertNotificationsJob;
use App\Jobs\Product\ProcessProductJob;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Check if price was changed and should be recorded in history
        if ($product->wasChanged('price') && $product->shouldRecordPriceHistory()) {
            $product->addPriceHistory($product->price);

            // Dispatch job to send price alert notifications
            SendPriceAlertNotificationsJob::dispatch($product->id);
        }

        // Dispatch job to process product attributes and grouping
        ProcessProductJob::dispatch($product->id);
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // Add initial price to history when product is created
        $product->addPriceHistory($product->price);

        // Dispatch job to process product attributes and grouping
        ProcessProductJob::dispatch($product->id);
    }
}
