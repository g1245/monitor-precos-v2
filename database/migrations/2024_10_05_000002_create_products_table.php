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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 16, 4)->default(0);
            $table->decimal('price_regular', 16, 4)->default(0);
            $table->string('sku')->nullable();
            $table->string('brand')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->vector('vector_search', 1536)->nullable();
            $table->text('deep_link')->nullable();
            $table->text('external_link')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('brand');
            $table->index('price');
            $table->index('is_active');
            $table->index('store_id');
            $table->unique('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};