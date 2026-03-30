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
            $table->decimal('highest_recorded_price', 10, 2)->nullable()->after('price_regular');
            $table->decimal('lowest_recorded_price', 10, 2)->nullable()->after('highest_recorded_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['highest_recorded_price', 'lowest_recorded_price']);
        });
    }
};
