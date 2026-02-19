<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ProductProcessorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:process {productId : The ID of the product to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a product synchronously';

    /**
     * Execute the console command.
     */
    public function handle(ProductProcessorService $processorService): void
    {
        $productId = $this->argument('productId');

        $product = Product::find($productId);

        if (!$product) {
            Log::warning('Product not found for processing', ['product_id' => $productId]);
            $this->error('Product not found');
            
            return;
        }

        $processorService->process($product);

        $this->info('Product processed successfully');
    }
}