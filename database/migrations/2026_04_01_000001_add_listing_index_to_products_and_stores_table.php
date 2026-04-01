<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Composite index to optimize the public product listing query:
     *
     *   SELECT products.* FROM products
     *   LEFT JOIN stores ON products.store_id = stores.id AND stores.has_public = 1
     *   WHERE stores.id IS NOT NULL AND is_active = 1 AND is_parent = 0
     *   ORDER BY created_at DESC LIMIT 10;
     *
     * Index column order rationale:
     *   1. is_active  — equality filter, highest selectivity applied first
     *   2. is_parent  — equality filter
     *   3. created_at — ORDER BY column, allows index scan in-order (no filesort)
     *
     * The stores.has_public index speeds up the JOIN condition evaluation.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'is_parent', 'created_at'], 'idx_products_listing');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->index('has_public', 'idx_stores_has_public');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_listing');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropIndex('idx_stores_has_public');
        });
    }
};
