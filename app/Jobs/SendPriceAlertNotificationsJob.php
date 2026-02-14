<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\PriceAlertService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPriceAlertNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $productId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = Product::find($this->productId);

        if (!$product) {
            Log::warning('Product not found for price alert job', ['product_id' => $this->productId]);
            return;
        }

        PriceAlertService::sendPriceAlerts($product);
    }
}
