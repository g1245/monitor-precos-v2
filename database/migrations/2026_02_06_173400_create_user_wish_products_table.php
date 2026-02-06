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
        Schema::create('user_wish_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('target_price', 10, 2)->nullable()->comment('Alert when price drops below this value. If null, just a wish without price alert');
            $table->boolean('is_active')->default(true)->comment('If price alert is active');
            $table->timestamp('last_notified_at')->nullable()->comment('Last time user was notified about price change');
            $table->timestamps();
            
            // Prevent duplicate wishes for same product
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_wish_products');
    }
};
