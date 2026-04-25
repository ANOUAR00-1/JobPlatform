<?php

namespace App\Console\Commands;

use App\Models\JobAlert;
use App\Models\Offre;
use App\Mail\JobAlertMail;
use App\Jobs\SendEmailJob;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendJobAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:send {frequency=daily}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send job alerts to candidats based on their preferences';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $frequency = $this->argument('frequency');
        
        $this->info("Sending {$frequency} job alerts...");

        // Get active alerts for this frequency
        $alerts = JobAlert::with('candidat.user')
            ->where('is_active', true)
            ->where('frequency', $frequency)
            ->get();

        $totalSent = 0;

        foreach ($alerts as $alert) {
            // Get new jobs since last alert
            $since = $alert->last_sent_at ?? Carbon::now()->subDay();
            
            $matchingJobs = Offre::with(['entreprise', 'ville'])
                ->where('statut', 'ouverte')
                ->where('created_at', '>=', $since)
                ->get()
                ->filter(function ($job) use ($alert) {
                    return $alert->matchesJob($job);
                });

            if ($matchingJobs->count() > 0) {
                // Send email
                $candidatEmail = $alert->candidat->user->email;
                SendEmailJob::dispatch($candidatEmail, new JobAlertMail($matchingJobs, $alert));

                // Update last_sent_at
                $alert->update(['last_sent_at' => Carbon::now()]);

                $totalSent++;
                $this->info("Sent alert to {$candidatEmail} ({$matchingJobs->count()} jobs)");
            }
        }

        $this->info("Total alerts sent: {$totalSent}");

        return 0;
    }
}
