<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('old_price_at')
                ->nullable()
                ->after('old_price')
                ->comment('Timestamp of the last old_price update, used to track how long ago the price changed.');

            $table->index(['price', 'old_price', 'old_price_at'], 'products_price_old_price_old_price_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_price_old_price_old_price_at_index');
            $table->dropColumn('old_price_at');
        });
    }
};
