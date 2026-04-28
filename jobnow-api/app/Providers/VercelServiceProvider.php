<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class VercelServiceProvider extends ServiceProvider
{
    /**
     * Register services for Vercel serverless environment.
     */
    public function register(): void
    {
        // Configure storage paths for Vercel's read-only filesystem
        if ($this->app->environment('production')) {
            // Set writable paths to /tmp
            $this->app->useStoragePath('/tmp/storage');
            
            // Ensure storage directories exist in /tmp
            $this->ensureStorageDirectoriesExist();
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Ensure required storage directories exist in /tmp
     */
    protected function ensureStorageDirectoriesExist(): void
    {
        $directories = [
            '/tmp/storage',
            '/tmp/storage/framework',
            '/tmp/storage/framework/cache',
            '/tmp/storage/framework/cache/data',
            '/tmp/storage/framework/sessions',
            '/tmp/storage/framework/views',
            '/tmp/storage/logs',
            '/tmp/storage/app',
            '/tmp/storage/app/public',
        ];

        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                @mkdir($directory, 0755, true);
            }
        }
    }
}
