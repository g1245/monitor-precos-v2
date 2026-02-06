<?php

namespace App\Jobs;

use App\Models\UserBrowsingHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TrackBrowsingHistoryJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $data
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserBrowsingHistory::create($this->data);
    }
}
