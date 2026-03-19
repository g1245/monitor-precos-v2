<?php

namespace App\Observers;

use App\Jobs\SendPriceAlertNotificationsJob;
use App\Jobs\Product\ProcessProductJob;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // Dispatch job to process product attributes and grouping
        ProcessProductJob::dispatch($product->id);

        // Add initial price to history when product is created (async)
        dispatch(function () use ($product): void {
            $product->addPriceHistory($product->price);
        });

        // Record audit log for product creation
        $this->writeAuditLog($product, 'created');
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Dispatch job to process product attributes and grouping
        ProcessProductJob::dispatch($product->id);

        if ($product->wasChanged('price')) {
            $previousPrice = $product->getOriginal('price');

            if ($previousPrice !== null) {
                $product->updateQuietly([
                    'old_price' => $previousPrice,
                ]);
            }
        }

        // Check if price was changed and should be recorded in history
        if ($product->wasChanged('price') && $product->shouldRecordPriceHistory()) {
            dispatch(function () use ($product): void {
                $product->addPriceHistory($product->price);
            });

            // Dispatch job to send price alert notifications
            SendPriceAlertNotificationsJob::dispatch($product->id);
        }

        // Record audit log for product update
        $this->writeAuditLog($product, 'updated');
    }

    /**
     * Write an audit log entry for the given product event.
     * Logs are written to the 'product-audit' channel (daily rotation, 2-day retention).
     *
     * @param Product $product The product that was created or updated.
     * @param string  $event   The event type ('created' or 'updated').
     */
    private function writeAuditLog(Product $product, string $event): void
    {
        Log::channel('product-audit')->info("Product {$event}", [
            'event'      => $event,
            'store_id'   => $product->store_id,
            'product_id' => $product->id,
            'snapshot'   => $product->getAttributes(),
        ]);
    }
}
