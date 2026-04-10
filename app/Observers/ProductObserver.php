<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductChangeLog;
use App\Services\ProductLifecycleService;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    public function __construct(
        private readonly ProductLifecycleService $lifecycle,
    ) {}

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->lifecycle->onCreated($product);
        $this->writeAuditLog($product, 'created');
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->lifecycle->onUpdated($product);
        $this->writeAuditLog($product, 'updated');
        $this->recordChangeLog($product);
    }

    /**
     * Record a before/after change log entry for the updated product,
     * saving only the fields that were actually modified (excluding updated_at).
     */
    private function recordChangeLog(Product $product): void
    {
        $changed = collect($product->getChanges())
            ->except('updated_at')
            ->keys();

        if ($changed->isEmpty()) {
            return;
        }

        $before = collect($product->getOriginal())->only($changed)->all();
        $after  = collect($product->getChanges())->only($changed)->all();

        ProductChangeLog::create([
            'product_id' => $product->id,
            'before'     => $before,
            'after'      => $after,
        ]);
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
