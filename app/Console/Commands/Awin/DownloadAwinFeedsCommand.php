<?php

namespace App\Console\Commands\Awin;

use App\Services\Awin\AwinFeedDownloadService;
use Illuminate\Console\Command;

/**
 * DownloadAwinFeedsCommand
 *
 * Console command to download Awin feeds that were updated in the last 24 hours.
 * Creates local CSV files and marks feeds with pending updates.
 */
class DownloadAwinFeedsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'awin:download-feeds 
                            {--hours=24 : Number of hours to look back for updated feeds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Awin feeds updated in the last X hours and save them locally';

    /**
     * Execute the console command.
     *
     * @param AwinFeedDownloadService $awinFeedDownloadService
     * @return int
     */
    public function handle(AwinFeedDownloadService $awinFeedDownloadService): int
    {
        $hours = (int) $this->option('hours');
        
        $this->info("Starting Awin feed downloads (last {$hours} hours)...");
        $this->newLine();
        
        $result = $awinFeedDownloadService->processRecentFeeds($hours);
        
        if ($result['downloaded'] === 0) {
            $this->info('No Awin feeds to download (no updates in the specified period)');
            return Command::SUCCESS;
        }
        
        if ($result['success']) {
            $this->info("✓ Successfully downloaded {$result['downloaded']} Awin feeds");
            return Command::SUCCESS;
        }
        
        $this->warn("⚠ Downloaded {$result['downloaded']} feeds with errors:");
        $this->newLine();
        
        foreach ($result['errors'] as $error) {
            $this->error("  • {$error}");
        }
        
        return Command::FAILURE;
    }
}
