<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `products` DROP COLUMN `discount_percentage`');
        DB::statement('ALTER TABLE `products` ADD COLUMN `discount_percentage` INT GENERATED ALWAYS AS (ROUND(((price_regular - price) / NULLIF(price_regular, 0) * 100), 0)) STORED AFTER `external_link`');
        DB::statement('CREATE INDEX `products_discount_percentage_index` ON `products` (`discount_percentage`)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `products` DROP INDEX `products_discount_percentage_index`');
        DB::statement('ALTER TABLE `products` DROP COLUMN `discount_percentage`');
        DB::statement('ALTER TABLE `products` ADD COLUMN `discount_percentage` INT GENERATED ALWAYS AS (ROUND(((price_regular - price) / price_regular * 100), 0)) STORED AFTER `external_link`');
        DB::statement('CREATE INDEX `products_discount_percentage_index` ON `products` (`discount_percentage`)');
    }
};
