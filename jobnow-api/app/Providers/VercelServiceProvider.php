<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class VercelServiceProvider extends ServiceProvider
{
    /**
     * Register services for Vercel serverless environment.
     */
    public function register(): void
    {
        // Only run in production (Vercel)
        if (!$this->app->environment('production')) {
            return;
        }

        // Set storage path to /tmp
        $this->app->useStoragePath('/tmp/storage');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only run in production (Vercel)
        if (!$this->app->environment('production')) {
            return;
        }

        // Create storage directories
        $this->createStorageDirectories();
    }

    /**
     * Create required storage directories in /tmp
     */
    protected function createStorageDirectories(): void
    {
        $directories = [
            storage_path(),
            storage_path('framework'),
            storage_path('framework/cache'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            storage_path('app'),
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                @mkdir($directory, 0755, true);
            }
        }
    }
}
