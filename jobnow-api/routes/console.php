<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: Close expired jobs daily at midnight
Schedule::command('jobs:close-expired')->daily();

// Schedule: Send daily job alerts at 9 AM
Schedule::command('alerts:send daily')->dailyAt('09:00');

// Schedule: Send weekly job alerts on Monday at 9 AM
Schedule::command('alerts:send weekly')->weeklyOn(1, '09:00');

// Schedule: Clear old cache entries weekly
Schedule::command('cache:prune-stale-tags')->weekly();
