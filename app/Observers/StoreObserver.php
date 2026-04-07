<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Store;

class StoreObserver
{
    /**
     * Handle the Store "updated" event.
     *
     * When has_public changes, propagate the new visibility status to all
     * products belonging to this store, keeping is_store_visible in sync.
     */
    public function updated(Store $store): void
    {
        if ($store->wasChanged('has_public')) {
            Product::where('store_id', $store->id)
                ->update(['is_store_visible' => $store->has_public]);
        }
    }
}
