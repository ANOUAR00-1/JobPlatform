<?php

// Simple test to see if PHP is working on Vercel
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'status' => 'success',
    'message' => 'PHP is working on Vercel!',
    'php_version' => phpversion(),
    'extensions' => get_loaded_extensions(),
    'writable_tmp' => is_writable('/tmp'),
    'vendor_exists' => file_exists(__DIR__.'/../vendor/autoload.php'),
]);
