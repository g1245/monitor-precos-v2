<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncProductViewsCommand extends Command
{
    protected $signature = 'app:sync-product-views';

    protected $description = 'Sync products views_count from user_browsing_history table';

    public function handle(): int
    {
        DB::statement('
            UPDATE products p
            INNER JOIN (
                SELECT product_id, COUNT(*) AS total
                FROM user_browsing_history
                GROUP BY product_id
            ) AS counts ON p.id = counts.product_id
            SET p.views_count = counts.total
        ');

        $this->info('Product views_count synced successfully.');

        return self::SUCCESS;
    }
}
