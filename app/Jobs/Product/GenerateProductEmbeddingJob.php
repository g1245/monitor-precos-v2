<?php

namespace App\Jobs\Product;

use App\Models\Product;
use App\Services\Embedding\EmbeddingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job to generate and store vector embeddings for a product.
 */
class GenerateProductEmbeddingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public string $queue = 'embeddings';

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $backoff = 10;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public int $timeout = 60;

    /**
     * The product instance.
     */
    private Product $product;

    /**
     * Create a new job instance.
     *
     * @param Product $product The product to generate embeddings for
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @param EmbeddingService $embeddingService
     * @return void
     */
    public function handle(EmbeddingService $embeddingService): void
    {
        Log::info('Generating embedding for product', [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
        ]);

        // Build text representation of the product for embedding
        $textToEmbed = $this->buildProductText();

        // Generate embedding
        $embedding = $embeddingService->generateEmbedding($textToEmbed);

        if ($embedding === null) {
            Log::error('Failed to generate embedding for product', [
                'product_id' => $this->product->id,
            ]);
            
            // Throw exception to trigger retry mechanism
            throw new \RuntimeException('Failed to generate embedding for product ' . $this->product->id);
        }

        // Update product with the embedding
        $this->product->update([
            'vector_search' => $embedding,
        ]);

        Log::info('Successfully generated embedding for product', [
            'product_id' => $this->product->id,
            'embedding_dimensions' => count($embedding),
        ]);
    }

    /**
     * Build text representation of the product for embedding generation.
     *
     * Combines product name, description, brand, and other relevant attributes.
     *
     * @return string The text to generate embeddings from
     */
    private function buildProductText(): string
    {
        $parts = [];

        // Add product name (most important)
        if (!empty($this->product->name)) {
            $parts[] = $this->product->name;
        }

        // Add brand
        if (!empty($this->product->brand)) {
            $parts[] = "Marca: {$this->product->brand}";
        }

        // Add description
        if (!empty($this->product->description)) {
            // Limit description length to avoid token limits
            $description = mb_substr($this->product->description, 0, 500);
            $parts[] = $description;
        }

        // Add SKU for searchability
        if (!empty($this->product->sku)) {
            $parts[] = "SKU: {$this->product->sku}";
        }

        // Add department names for context
        $departments = $this->product->departments()->pluck('name')->toArray();
        if (!empty($departments)) {
            $parts[] = "Departamentos: " . implode(', ', $departments);
        }

        // Combine all parts with spaces
        return implode(' ', $parts);
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job failed after all retries', [
            'product_id' => $this->product->id,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
