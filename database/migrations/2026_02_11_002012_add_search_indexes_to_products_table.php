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
        // FULLTEXT indexes are only supported in MySQL/MariaDB
        if (config('database.default') !== 'sqlite') {
            Schema::table('products', function (Blueprint $table) {
                // FULLTEXT index for text search optimization
                DB::statement('ALTER TABLE products ADD FULLTEXT search_index (name, description, brand)');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FULLTEXT indexes are only supported in MySQL/MariaDB
        if (config('database.default') !== 'sqlite') {
            Schema::table('products', function (Blueprint $table) {
                DB::statement('ALTER TABLE products DROP INDEX search_index');
            });
        }
    }
};
