<?php

namespace App\Observers;

use App\Jobs\SendPriceAlertNotificationsJob;
use App\Jobs\Product\ProcessProductJob;
use App\Models\Product;
use App\Models\ProductAuditLog;

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
        $this->upsertAuditLog($product, 'created');
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Dispatch job to process product attributes and grouping
        ProcessProductJob::dispatch($product->id);

        // Check if price was changed and should be recorded in history
        if ($product->wasChanged('price') && $product->shouldRecordPriceHistory()) {
            dispatch(function () use ($product): void {
                $product->addPriceHistory($product->price);
            });

            // Dispatch job to send price alert notifications
            SendPriceAlertNotificationsJob::dispatch($product->id);
        }

        // Record audit log for product update
        $this->upsertAuditLog($product, 'updated');
    }

    /**
     * Create or update the audit log entry for the given product.
     * The log is unique per store/product pair and expires after two days.
     *
     * @param Product $product The product that was created or updated.
     * @param string  $event   The event type ('created' or 'updated').
     */
    private function upsertAuditLog(Product $product, string $event): void
    {
        ProductAuditLog::updateOrCreate(
            [
                'store_id'   => $product->store_id,
                'product_id' => $product->id,
            ],
            [
                'event'            => $event,
                'product_snapshot' => $product->getAttributes(),
                'expires_at'       => now()->addDays(2),
            ]
        );
    }
}
