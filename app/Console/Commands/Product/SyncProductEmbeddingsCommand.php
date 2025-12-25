<?php

namespace App\Console\Commands\Product;

use App\Jobs\Product\GenerateProductEmbeddingJob;
use App\Models\Product;
use App\Services\Embedding\EmbeddingService;
use Illuminate\Console\Command;

/**
 * Command to synchronize vector embeddings for products.
 * 
 * Finds products without embeddings (vector_search is null) and dispatches
 * jobs to generate them using the text-embeddings-inference API.
 */
class SyncProductEmbeddingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync-embeddings
                            {--limit= : Maximum number of products to process}
                            {--batch-size=100 : Number of jobs to dispatch at once}
                            {--force : Process all products, even those with existing embeddings}
                            {--check-health : Check embeddings service health before processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize vector embeddings for products without embeddings';

    /**
     * Execute the console command.
     */
    public function handle(EmbeddingService $embeddingService): int
    {
        // Check service health if requested
        if ($this->option('check-health')) {
            $this->info('Checking embeddings service health...');
            
            if (!$embeddingService->isHealthy()) {
                $this->error('Embeddings service is not available. Please check the service status.');
                return self::FAILURE;
            }
            
            $this->info('✓ Embeddings service is healthy');
            
            // Display service info
            $info = $embeddingService->getInfo();
            if ($info) {
                $this->line('Service Info:');
                $this->table(['Key', 'Value'], collect($info)->map(function ($value, $key) {
                    return [$key, is_array($value) ? json_encode($value) : $value];
                })->toArray());
            }
            $this->newLine();
        }

        // Build query for products
        $query = Product::query();
        
        if (!$this->option('force')) {
            $query->whereNull('vector_search');
        }

        // Apply limit if specified
        if ($limit = $this->option('limit')) {
            $query->limit((int) $limit);
        }

        // Get total count
        $totalProducts = $query->count();

        if ($totalProducts === 0) {
            $this->info('No products found that need embedding synchronization.');
            return self::SUCCESS;
        }

        $this->info("Found {$totalProducts} product(s) to process.");
        
        // Ask for confirmation if processing many products
        if ($totalProducts > 100 && !$this->option('no-interaction')) {
            if (!$this->confirm("Do you want to dispatch {$totalProducts} jobs?")) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
        }

        $batchSize = (int) $this->option('batch-size');
        $dispatched = 0;

        // Create progress bar
        $progressBar = $this->output->createProgressBar($totalProducts);
        $progressBar->start();

        // Process products in chunks to avoid memory issues
        $query->chunk($batchSize, function ($products) use (&$dispatched, $progressBar) {
            foreach ($products as $product) {
                // Dispatch job to queue
                GenerateProductEmbeddingJob::dispatch($product);
                $dispatched++;
                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✓ Successfully dispatched {$dispatched} job(s) to generate embeddings.");
        $this->line('Jobs will be processed by the embeddings queue worker.');
        
        // Show queue status hint
        $this->newLine();
        $this->comment('Tip: Monitor job progress with: php artisan queue:work --queue=embeddings');
        $this->comment('     Or check failed jobs with: php artisan queue:failed');

        return self::SUCCESS;
    }
}
