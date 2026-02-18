<?php

namespace App\Jobs\Product;

use App\Models\Product;
use App\Services\ProductProcessorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $productId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ProductProcessorService $processorService): void
    {
        $product = Product::find($this->productId);

        if (!$product) {
            Log::warning('Product not found for processing', ['product_id' => $this->productId]);
            return;
        }

        $processorService->process($product);
    }
}
