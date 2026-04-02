<?php

namespace App\Console\Commands\Product;

use App\Jobs\Product\SyncProductFromRabbitMQJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumeProductsQueueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume messages from the RabbitMQ "products" queue and dispatch sync jobs';

    /**
     * Name of the RabbitMQ queue to consume from.
     */
    private const QUEUE_NAME = 'products';

    /**
     * Execute the console command.
     * Opens a persistent connection to RabbitMQ and processes messages indefinitely.
     */
    public function handle(): int
    {
        $this->info('Starting RabbitMQ consumer for queue: ' . self::QUEUE_NAME);

        $connection = $this->createConnection();

        if (!$connection) {
            $this->error('Could not establish a connection to RabbitMQ.');

            return self::FAILURE;
        }

        $channel = $connection->channel();

        // Declare queue passively — assumes it already exists on the broker.
        // Change to channel->queue_declare() if the consumer should also create it.
        $channel->queue_declare(
            queue: self::QUEUE_NAME,
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false,
        );

        // Process one message at a time to avoid overwhelming the job queue.
        $channel->basic_qos(prefetch_size: 0, prefetch_count: 1, a_global: false);

        $this->info('Waiting for messages. Press CTRL+C to stop.');
        Log::info('RabbitMQ consumer started', ['queue' => self::QUEUE_NAME]);

        $channel->basic_consume(
            queue: self::QUEUE_NAME,
            consumer_tag: '',
            no_local: false,
            no_ack: false,
            exclusive: false,
            nowait: false,
            callback: function (AMQPMessage $message): void {
                $this->processMessage($message);
            },
        );

        try {
            while ($channel->is_consuming()) {
                $channel->wait();
            }
        } catch (\Throwable $e) {
            Log::error('RabbitMQ consumer encountered an error', [
                'queue' => self::QUEUE_NAME,
                'error' => $e->getMessage(),
            ]);

            $this->error('Consumer error: ' . $e->getMessage());
        } finally {
            $channel->close();
            $connection->close();
            $this->info('RabbitMQ connection closed.');
            Log::info('RabbitMQ consumer stopped', ['queue' => self::QUEUE_NAME]);
        }

        return self::SUCCESS;
    }

    /**
     * Process a single RabbitMQ message.
     * Decodes the payload, dispatches a sync job, then ACKs the message.
     *
     * Expected payload format:
     * {"product_id": "69c435acf3e8899bea77d997", "store": "Nike BR", "action": "update", "published_at": "2026-04-02T02:51:08.475175"}
     *
     * @param AMQPMessage $message
     */
    private function processMessage(AMQPMessage $message): void
    {
        $payload = json_decode($message->getBody(), true);

        if (!$this->isValidPayload($payload)) {
            Log::warning('Invalid or malformed RabbitMQ message, discarding', [
                'queue' => self::QUEUE_NAME,
                'body' => $message->getBody(),
            ]);

            $this->warn('Discarding invalid message: ' . $message->getBody());

            // NACK without requeue to avoid infinite loops on bad messages.
            $message->nack(requeue: false);

            return;
        }

        $awProductId = $payload['product_id'];
        $storeName = $payload['store'];

        $this->line("Dispatching sync for product [{$awProductId}] from store [{$storeName}]");

        Log::info('Dispatching SyncProductFromRabbitMQJob', [
            'aw_product_id' => $awProductId,
            'store_name' => $storeName,
            'action' => $payload['action'] ?? null,
            'published_at' => $payload['published_at'] ?? null,
        ]);

        SyncProductFromRabbitMQJob::dispatch($awProductId, $storeName);

        // ACK after dispatching — removes the message from the queue.
        $message->ack();
    }

    /**
     * Validate that the decoded payload contains the required fields.
     *
     * @param mixed $payload
     * @return bool
     */
    private function isValidPayload(mixed $payload): bool
    {
        return is_array($payload)
            && !empty($payload['product_id'])
            && is_string($payload['product_id'])
            && !empty($payload['store'])
            && is_string($payload['store']);
    }

    /**
     * Create and return an AMQP connection using config values.
     *
     * @return AMQPStreamConnection|null
     */
    private function createConnection(): ?AMQPStreamConnection
    {
        try {
            return new AMQPStreamConnection(
                host: config('services.rabbitmq.host'),
                port: config('services.rabbitmq.port'),
                user: config('services.rabbitmq.user'),
                password: config('services.rabbitmq.password'),
                vhost: config('services.rabbitmq.vhost'),
            );
        } catch (\Throwable $e) {
            Log::error('Failed to connect to RabbitMQ', [
                'host' => config('services.rabbitmq.host'),
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
