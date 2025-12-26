<?php

namespace App\Console\Commands\Awin;

use App\Jobs\Feed\DownloadAwinFeedJob;
use App\Services\Feed\FeedManagerService;
use Illuminate\Console\Command;

/**
 * DownloadAwinFeedsCommand
 *
 * Console command to dispatch jobs for downloading Awin feeds 
 * that were updated in the last X hours.
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
    protected $description = 'Dispatch jobs to download Awin feeds updated in the last X hours';

    /**
     * Execute the console command.
     *
     * @param FeedManagerService $feedManager
     * @return int
     */
    public function handle(FeedManagerService $feedManager): int
    {
        $hours = (int) $this->option('hours');
        
        $this->info("Finding Awin feeds to download (last {$hours} hours)...");
        $this->newLine();
        
        $feeds = $feedManager->getRecentlyUpdatedFeeds($hours, 'awin');
        
        if ($feeds->isEmpty()) {
            $this->info('No Awin feeds to download (no updates in the specified period)');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$feeds->count()} feed(s). Dispatching download jobs...");
        $this->newLine();
        
        $dispatched = 0;
        foreach ($feeds as $feed) {
            DownloadAwinFeedJob::dispatch($feed);
            $this->line("  ✓ Dispatched job for: {$feed->store->name} (Feed ID: {$feed->id})");
            $dispatched++;
        }
        
        $this->newLine();
        $this->info("Successfully dispatched {$dispatched} download job(s)");
        
        $feedManager->logFeedAction('awin_download_jobs_dispatched', [
            'hours' => $hours,
            'dispatched' => $dispatched,
        ]);
        
        return Command::SUCCESS;
    }
}
