<?php

namespace App\Console\Commands;

use App\Models\Offre;
use App\Services\CacheService;
use Illuminate\Console\Command;

class CloseExpiredJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close job offers that have passed their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired job offers...');

        $expiredJobs = Offre::where('date_expiration', '<', now())
            ->where('statut', 'ouverte')
            ->get();

        if ($expiredJobs->isEmpty()) {
            $this->info(' No expired jobs found.');
            return 0;
        }

        $count = $expiredJobs->count();
        $this->info("Found {$count} expired job(s). Closing...");

        foreach ($expiredJobs as $job) {
            $job->update(['statut' => 'fermee']);
            $this->line("  ✓ Closed: {$job->titre} (ID: {$job->id})");
        }

        // Clear job caches
        CacheService::clearJobCaches();

        $this->info(" Successfully closed {$count} expired job offer(s).");
        
        return 0;
    }
}
