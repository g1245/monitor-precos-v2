<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Recria a coluna virtual `discount_percentage` usando `highest_recorded_price`
     * como base de cálculo, substituindo `price_regular`.
     *
     * Isso garante que o percentual de desconto reflita a variação real do preço
     * ao longo do tempo, e não o preço regular informado pelo vendedor — que pode
     * ser inflado artificialmente antes de datas promocionais.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `products` DROP INDEX `products_discount_percentage_index`');
        DB::statement('ALTER TABLE `products` DROP COLUMN `discount_percentage`');
        DB::statement('ALTER TABLE `products` ADD COLUMN `discount_percentage` INT GENERATED ALWAYS AS (ROUND(((highest_recorded_price - price) / NULLIF(highest_recorded_price, 0) * 100), 0)) STORED AFTER `external_link`');
        DB::statement('CREATE INDEX `products_discount_percentage_index` ON `products` (`discount_percentage`)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `products` DROP INDEX `products_discount_percentage_index`');
        DB::statement('ALTER TABLE `products` DROP COLUMN `discount_percentage`');
        DB::statement('ALTER TABLE `products` ADD COLUMN `discount_percentage` INT GENERATED ALWAYS AS (ROUND(((price_regular - price) / NULLIF(price_regular, 0) * 100), 0)) STORED AFTER `external_link`');
        DB::statement('CREATE INDEX `products_discount_percentage_index` ON `products` (`discount_percentage`)');
    }
};
