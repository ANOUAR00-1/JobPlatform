<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    // Cache durations (in seconds)
    const CACHE_JOBS_LIST = 3600;        // 1 hour
    const CACHE_JOB_DETAIL = 3600;       // 1 hour
    const CACHE_CITIES = 86400;          // 24 hours
    const CACHE_USER_PROFILE = 3600;     // 1 hour
    const CACHE_ENTREPRISE_JOBS = 1800;  // 30 minutes
    const CACHE_STATS = 1800;            // 30 minutes

    /**
     * Get cache key for jobs list
     */
    public static function jobsListKey(array $filters = []): string
    {
        $filterString = empty($filters) ? 'all' : md5(json_encode($filters));
        return "jobs:list:{$filterString}";
    }

    /**
     * Get cache key for single job
     */
    public static function jobDetailKey(int $jobId): string
    {
        return "job:detail:{$jobId}";
    }

    /**
     * Get cache key for cities
     */
    public static function citiesKey(): string
    {
        return 'cities:all';
    }

    /**
     * Get cache key for user profile
     */
    public static function userProfileKey(int $userId): string
    {
        return "user:profile:{$userId}";
    }

    /**
     * Get cache key for entreprise jobs
     */
    public static function entrepriseJobsKey(int $entrepriseId): string
    {
        return "entreprise:jobs:{$entrepriseId}";
    }

    /**
     * Clear all job-related caches
     */
    public static function clearJobCaches(): void
    {
        Cache::tags(['jobs'])->flush();
    }

    /**
     * Clear user profile cache
     */
    public static function clearUserCache(int $userId): void
    {
        Cache::forget(self::userProfileKey($userId));
    }

    /**
     * Clear entreprise jobs cache
     */
    public static function clearEntrepriseJobsCache(int $entrepriseId): void
    {
        Cache::forget(self::entrepriseJobsKey($entrepriseId));
    }

    /**
     * Remember with tags (for grouped cache clearing)
     */
    public static function rememberWithTags(array $tags, string $key, int $ttl, callable $callback)
    {
        if (config('cache.default') === 'database' || config('cache.default') === 'file') {
            // File/Database cache doesn't support tags, use regular remember
            return Cache::remember($key, $ttl, $callback);
        }

        return Cache::tags($tags)->remember($key, $ttl, $callback);
    }
}
