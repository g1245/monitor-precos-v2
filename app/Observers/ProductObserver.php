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

        // Initialize lowest recorded price tracking based on price
        if ($product->price !== null) {
            $product->updateQuietly([
                'lowest_recorded_price'  => $product->price,
            ]);
        }

        // Initialize highest recorded price tracking based on price_regular
        // if ($product->price_regular !== null) {
        //     $product->updateQuietly([
        //         'highest_recorded_price' => $product->price_regular,
        //     ]);
        // }

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

            if ($previousPrice != $product->price) {
                $product->updateQuietly([
                    'old_price'    => $previousPrice,
                    'old_price_at' => now(),
                ]);
            }
        }

        // Check if price was changed and should be recorded in history
        if ($product->wasChanged('price') && $product->shouldRecordPriceHistory()) {
            $updates = [];

            if ($product->highest_recorded_price === null || $product->price > $product->highest_recorded_price) {
                $updates['highest_recorded_price'] = $product->price;
            }

            if ($product->lowest_recorded_price === null || $product->price < $product->lowest_recorded_price) {
                $updates['lowest_recorded_price'] = $product->price;
            }

            if (!empty($updates)) {
                $product->updateQuietly($updates);
            }

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
