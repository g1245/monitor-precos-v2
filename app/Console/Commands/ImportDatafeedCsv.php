<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDatafeedCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datafeed:import {file : Path to the CSV file to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from a CSV file with pipe separator to datafeeds_items table';

    /**
     * CSV column mapping to database fields.
     *
     * @var array<string, string>
     */
    protected array $columnMapping = [
        'aw_deep_link' => 'aw_deep_link',
        'product_name' => 'product_name',
        'aw_product_id' => 'aw_product_id',
        'merchant_product_id' => 'merchant_product_id',
        'merchant_image_url' => 'merchant_image_url',
        'description' => 'description',
        'merchant_category' => 'merchant_category',
        'search_price' => 'search_price',
        'merchant_name' => 'merchant_name',
        'merchant_id' => 'merchant_id',
        'category_name' => 'category_name',
        'category_id' => 'category_id',
        'aw_image_url' => 'aw_image_url',
        'currency' => 'currency',
        'store_price' => 'store_price',
        'delivery_cost' => 'delivery_cost',
        'merchant_deep_link' => 'merchant_deep_link',
        'language' => 'language',
        'last_updated' => 'last_updated',
        'display_price' => 'display_price',
        'data_feed_id' => 'data_feed_id',
        'product_model' => 'product_model',
        'model_number' => 'model_number',
        'dimensions' => 'dimensions',
        'brand_name' => 'brand_name',
        'brand_id' => 'brand_id',
        'colour' => 'colour',
        'product_short_description' => 'product_short_description',
        'specifications' => 'specifications',
        'condition' => 'condition',
        'keywords' => 'keywords',
        'promotional_text' => 'promotional_text',
        'product_type' => 'product_type',
        'commission_group' => 'commission_group',
        'merchant_product_category_path' => 'merchant_product_category_path',
        'merchant_product_second_category' => 'merchant_product_second_category',
        'merchant_product_third_category' => 'merchant_product_third_category',
        'rrp_price' => 'rrp_price',
        'saving' => 'saving',
        'savings_percent' => 'savings_percent',
        'base_price' => 'base_price',
        'base_price_amount' => 'base_price_amount',
        'base_price_text' => 'base_price_text',
        'product_price_old' => 'product_price_old',
        'delivery_restrictions' => 'delivery_restrictions',
        'delivery_weight' => 'delivery_weight',
        'warranty' => 'warranty',
        'terms_of_contract' => 'terms_of_contract',
        'delivery_time' => 'delivery_time',
        'in_stock' => 'in_stock',
        'stock_quantity' => 'stock_quantity',
        'valid_from' => 'valid_from',
        'valid_to' => 'valid_to',
        'is_for_sale' => 'is_for_sale',
        'web_offer' => 'web_offer',
        'pre_order' => 'pre_order',
        'stock_status' => 'stock_status',
        'size_stock_status' => 'size_stock_status',
        'size_stock_amount' => 'size_stock_amount',
        'merchant_thumb_url' => 'merchant_thumb_url',
        'large_image' => 'large_image',
        'alternate_image' => 'alternate_image',
        'aw_thumb_url' => 'aw_thumb_url',
        'alternate_image_two' => 'alternate_image_two',
        'alternate_image_three' => 'alternate_image_three',
        'alternate_image_four' => 'alternate_image_four',
        'reviews' => 'reviews',
        'average_rating' => 'average_rating',
        'rating' => 'rating',
        'number_available' => 'number_available',
        'custom_1' => 'custom_1',
        'custom_2' => 'custom_2',
        'custom_3' => 'custom_3',
        'custom_4' => 'custom_4',
        'custom_5' => 'custom_5',
        'custom_6' => 'custom_6',
        'custom_7' => 'custom_7',
        'custom_8' => 'custom_8',
        'custom_9' => 'custom_9',
        'ean' => 'ean',
        'isbn' => 'isbn',
        'upc' => 'upc',
        'mpn' => 'mpn',
        'parent_product_id' => 'parent_product_id',
        'product_GTIN' => 'product_GTIN',
        'basket_link' => 'basket_link',
        'Fashion:suitable_for' => 'fashion_suitable_for',
        'Fashion:category' => 'fashion_category',
        'Fashion:size' => 'fashion_size',
        'Fashion:material' => 'fashion_material',
        'Fashion:pattern' => 'fashion_pattern',
        'Fashion:swatch' => 'fashion_swatch',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = $this->argument('file');

        // Validate file exists
        if (! file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return Command::FAILURE;
        }

        // Validate file is readable
        if (! is_readable($filePath)) {
            $this->error("File is not readable: {$filePath}");

            return Command::FAILURE;
        }

        $this->info("Starting import from: {$filePath}");

        try {
            $imported = $this->importCsv($filePath);
            $this->info("Successfully imported {$imported} products.");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error importing CSV: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }

    /**
     * Import CSV file to database.
     *
     * @return int Number of imported records
     */
    protected function importCsv(string $filePath): int
    {
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw new \RuntimeException("Unable to open file: {$filePath}");
        }

        $headers = [];
        $imported = 0;
        $lineNumber = 0;
        $batchSize = 500;
        $batch = [];

        while (($row = fgetcsv($handle, 0, '|')) !== false) {
            $lineNumber++;

            // First row contains headers
            if ($lineNumber === 1) {
                $headers = $row;
                $this->info('Found '.count($headers).' columns in CSV.');

                continue;
            }

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Map CSV row to database fields
            $data = $this->mapRowToData($headers, $row);

            $batch[] = $data;

            // Insert in batches for better performance
            if (count($batch) >= $batchSize) {
                $this->insertBatch($batch);
                $imported += count($batch);
                $this->info("Imported {$imported} products...");
                $batch = [];
            }
        }

        // Insert remaining items
        if (! empty($batch)) {
            $this->insertBatch($batch);
            $imported += count($batch);
        }

        fclose($handle);

        return $imported;
    }

    /**
     * Map CSV row to database fields.
     *
     * @param  array<int, string>  $headers
     * @param  array<int, string>  $row
     * @return array<string, string|null>
     */
    protected function mapRowToData(array $headers, array $row): array
    {
        $data = [];

        foreach ($headers as $index => $header) {
            // Get the database field name from mapping
            if (isset($this->columnMapping[$header])) {
                $fieldName = $this->columnMapping[$header];
                $value = $row[$index] ?? null;

                // Store empty strings as null
                $data[$fieldName] = ($value === '' || $value === null) ? null : $value;
            }
        }

        // Add timestamps
        $now = now();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        return $data;
    }

    /**
     * Insert batch of records into database.
     *
     * @param  array<int, array<string, string|null>>  $batch
     */
    protected function insertBatch(array $batch): void
    {
        DB::table('datafeeds_items')->insert($batch);
    }
}
