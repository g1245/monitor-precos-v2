<?php

namespace App\Console\Commands\Awin;

use App\Services\Awin\AwinFeedListService;
use Illuminate\Console\Command;

/**
 * SyncAwinFeedListCommand
 *
 * Console command to synchronize the Awin feed list from the remote CSV.
 * Downloads the feed list and updates Store and StoreFeed records.
 */
class SyncAwinFeedListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'awin:sync-feeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Awin feed list from remote CSV and update Store and StoreFeed records';

    /**
     * Execute the console command.
     *
     * @param AwinFeedListService $awinFeedListService
     * @return int
     */
    public function handle(AwinFeedListService $awinFeedListService): int
    {
        $this->info('Starting Awin feed list synchronization...');
        $this->newLine();
        
        $result = $awinFeedListService->processFeedList();
        
        if ($result['success']) {
            $this->info("✓ Successfully processed {$result['processed']} Awin feeds");
            return Command::SUCCESS;
        }
        
        $this->warn("⚠ Processed {$result['processed']} feeds with errors:");
        $this->newLine();
        
        foreach ($result['errors'] as $error) {
            $this->error("  • {$error}");
        }
        
        return Command::FAILURE;
    }
}
