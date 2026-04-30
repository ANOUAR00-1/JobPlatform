<?php

// Handle CORS preflight requests FIRST
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    
    // Allow all Vercel deployments and localhost
    if (preg_match('/^https?:\/\/(localhost|.*\.vercel\.app)/', $origin)) {
        header("Access-Control-Allow-Origin: $origin");
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }
    
    http_response_code(204);
    exit(0);
}

// Set CORS headers for all requests
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (preg_match('/^https?:\/\/(localhost|.*\.vercel\.app)/', $origin)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Expose-Headers: Content-Length, Content-Type');
}

// Minimal Laravel bootstrap for Vercel
define('LARAVEL_START', microtime(true));

// Disable error display in production
ini_set('display_errors', '0');
error_reporting(0);

try {
    // Create /tmp storage directories
    $dirs = ['/tmp/storage', '/tmp/storage/framework', '/tmp/storage/framework/cache', 
             '/tmp/storage/framework/cache/data', '/tmp/storage/framework/sessions', 
             '/tmp/storage/framework/views', '/tmp/storage/logs', '/tmp/storage/app'];
    
    foreach ($dirs as $dir) {
        @mkdir($dir, 0755, true);
    }

    // Load Composer autoloader
    if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
        http_response_code(500);
        die('Composer dependencies not installed');
    }
    
    require __DIR__.'/../vendor/autoload.php';

    // Bootstrap Laravel
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    // Handle the request (Laravel 12 style)
    $app->handleRequest(
        \Illuminate\Http\Request::capture()
    );
    
} catch (\Throwable $e) {
    // Log error to stderr (Vercel logs)
    error_log('Laravel Error: ' . $e->getMessage());
    error_log('File: ' . $e->getFile() . ':' . $e->getLine());
    
    // Return JSON error for debugging
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Application Error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
}
