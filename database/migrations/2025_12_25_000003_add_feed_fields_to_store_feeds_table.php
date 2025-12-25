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
        Schema::table('store_feeds', function (Blueprint $table) {
            $table->boolean('has_pending_update')->default(false)->after('last_updated_at');
            
            $table->index('has_pending_update');
            $table->index('last_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_feeds', function (Blueprint $table) {
            $table->dropIndex(['has_pending_update']);
            $table->dropIndex(['last_updated_at']);
            
            $table->dropColumn('has_pending_update');
        });
    }
};
