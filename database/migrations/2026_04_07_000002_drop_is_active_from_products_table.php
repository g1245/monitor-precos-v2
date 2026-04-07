<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drops is_active from products (visibility is now controlled by
     * is_store_visible, synced from stores.has_public via StoreObserver).
     * Rebuilds the listing index replacing is_active with is_store_visible.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_listing');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->index(['is_store_visible', 'is_parent', 'created_at'], 'idx_products_listing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_listing');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('is_parent');
            $table->index(['is_active', 'is_parent', 'created_at'], 'idx_products_listing');
        });
    }
};
