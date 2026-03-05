<?php

namespace App\Services\ProductProcessors;

use App\Models\Product;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerInterface;

abstract class BaseProductProcessor implements ProductProcessorInterface
{
    /**
     * The internal store name that this processor handles.
     */
    protected string $storeInternalName;

    /**
     * Cached logger instance per processor class.
     */
    private ?LoggerInterface $loggerInstance = null;

    /**
     * Return a logger that writes simultaneously to the processor-specific
     * file and to the shared processors.log file.
     *
     * The files are resolved dynamically from $storeInternalName, so no
     * entry in config/logging.php is required for new processors.
     *
     * @return LoggerInterface
     */
    protected function logger(): LoggerInterface
    {
        if ($this->loggerInstance !== null) {
            return $this->loggerInstance;
        }

        $name = 'processor-' . $this->storeInternalName;
        $days = 2;

        $logger = new Logger($name);
        $logger->pushProcessor(new PsrLogMessageProcessor());

        // Per-processor rotating file: logs/processor-{store}.log
        $logger->pushHandler(
            new RotatingFileHandler(
                storage_path('logs/' . $name . '.log'),
                $days
            )
        );

        // Shared rotating file: logs/processors.log
        $logger->pushHandler(
            new RotatingFileHandler(
                storage_path('logs/processors.log'),
                $days
            )
        );

        return $this->loggerInstance = $logger;
    }

    /**
     * Process a product according to store-specific rules.
     *
     * @param Product $product The product to process
     * @return void
     */
    abstract public function process(Product $product): void;

    /**
     * Check if this processor can handle the given store.
     *
     * @param int $storeId The store ID to check
     * @return bool
     */
    public function canHandle(int $storeId): bool
    {
        $store = \App\Models\Store::find($storeId);

        if (!$store) {
            return false;
        }

        return $store->internal_name === $this->storeInternalName;
    }
}
