<?php

namespace App\Services\Embedding;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for generating text embeddings using HuggingFace text-embeddings-inference API.
 */
class EmbeddingService
{
    /**
     * The base URL for the embeddings API.
     */
    private string $baseUrl;

    /**
     * Request timeout in seconds.
     */
    private int $timeout;

    /**
     * Expected embedding dimensions.
     */
    private int $dimensions;

    /**
     * Create a new EmbeddingService instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.embedding.url');
        $this->timeout = config('services.embedding.timeout', 30);
        $this->dimensions = config('services.embedding.dimensions', 384);
    }

    /**
     * Generate embeddings for a single text.
     *
     * @param string $text The text to generate embeddings for
     * @return array|null The embedding vector or null on failure
     */
    public function generateEmbedding(string $text): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/embed", [
                    'inputs' => $text,
                ]);

            if (!$response->successful()) {
                Log::error('Failed to generate embedding', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();
            
            // The API returns embeddings in different formats depending on single or batch
            // For single input: returns array of floats directly
            // For batch: returns array of arrays
            $embedding = is_array($data[0] ?? null) && is_array($data[0][0] ?? null) 
                ? $data[0][0] 
                : ($data[0] ?? $data);

            if (!is_array($embedding) || count($embedding) !== $this->dimensions) {
                Log::error('Invalid embedding dimensions', [
                    'expected' => $this->dimensions,
                    'received' => count($embedding ?? []),
                ]);
                return null;
            }

            return $embedding;
        } catch (ConnectionException $e) {
            Log::error('Connection error generating embedding', [
                'message' => $e->getMessage(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Unexpected error generating embedding', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Generate embeddings for multiple texts in batch.
     *
     * @param array<string> $texts Array of texts to generate embeddings for
     * @return array Array of embeddings (same order as input)
     */
    public function generateBatchEmbeddings(array $texts): array
    {
        if (empty($texts)) {
            return [];
        }

        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/embed", [
                    'inputs' => $texts,
                ]);

            if (!$response->successful()) {
                Log::error('Failed to generate batch embeddings', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

            $data = $response->json();
            
            // Validate each embedding in the batch
            $embeddings = [];
            foreach ($data as $embedding) {
                if (!is_array($embedding) || count($embedding) !== $this->dimensions) {
                    Log::warning('Invalid embedding in batch', [
                        'expected_dimensions' => $this->dimensions,
                        'received_dimensions' => count($embedding ?? []),
                    ]);
                    $embeddings[] = null;
                } else {
                    $embeddings[] = $embedding;
                }
            }

            return $embeddings;
        } catch (ConnectionException $e) {
            Log::error('Connection error generating batch embeddings', [
                'message' => $e->getMessage(),
                'texts_count' => count($texts),
            ]);
            return [];
        } catch (\Exception $e) {
            Log::error('Unexpected error generating batch embeddings', [
                'message' => $e->getMessage(),
                'texts_count' => count($texts),
            ]);
            return [];
        }
    }

    /**
     * Check if the embeddings service is available.
     *
     * @return bool True if service is healthy, false otherwise
     */
    public function isHealthy(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('Embeddings service health check failed', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get service information.
     *
     * @return array|null Service info or null on failure
     */
    public function getInfo(): ?array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/info");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        } catch (\Exception $e) {
            Log::warning('Failed to get embeddings service info', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
