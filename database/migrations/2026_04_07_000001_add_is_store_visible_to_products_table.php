<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_store_visible')->default(true)->after('is_active')->index();
        });

        // Sync existing products with their store's current has_public value
        DB::statement('
            UPDATE products p
            JOIN stores s ON p.store_id = s.id
            SET p.is_store_visible = s.has_public
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_store_visible']);
            $table->dropColumn('is_store_visible');
        });
    }
};
