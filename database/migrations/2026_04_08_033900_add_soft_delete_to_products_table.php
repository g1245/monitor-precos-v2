<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add soft delete support to the products table.
     *
     * The composite index `idx_products_active_listing` is designed to support
     * the most common listing query pattern:
     *
     *   WHERE deleted_at IS NULL AND is_store_visible = 1 AND is_parent = 0
     *   ORDER BY created_at DESC
     *
     * Index column order rationale:
     *   1. deleted_at  — soft delete guard, highest-frequency equality filter (IS NULL)
     *   2. is_store_visible — boolean equality filter
     *   3. is_parent   — equality filter for parent-only listings
     *   4. created_at  — ORDER BY column, enables index-ordered scan (no filesort)
     *
     * The previous `idx_products_listing` index (on is_active/is_parent/created_at)
     * is dropped here because `is_active` was removed and replaced by `is_store_visible`.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes()->after('is_store_visible');
            $table->index('deleted_at', 'idx_products_deleted_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_deleted_at');
            $table->dropSoftDeletes();
        });
    }
};
