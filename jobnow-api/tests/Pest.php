<?php

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

uses(TestCase::class)->in('Feature', 'Unit');

// Mock Turnstile validation for all tests
beforeEach(function () {
    // Mock Cloudflare Turnstile API to always return success
    Http::fake([
        'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
        ], 200),
    ]);
});
