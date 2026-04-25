<?php

use App\Http\Helpers\ApiResponse;

if (!function_exists('api_success')) {
    function api_success($data = null, string $message = 'Success', int $statusCode = 200)
    {
        return ApiResponse::success($data, $message, $statusCode);
    }
}

if (!function_exists('api_error')) {
    function api_error(string $message, string $errorCode = 'ERROR', int $statusCode = 500, array $details = [])
    {
        return ApiResponse::error($message, $errorCode, $statusCode, $details);
    }
}

if (!function_exists('api_validation_error')) {
    function api_validation_error(string $message = 'Validation failed', array $errors = [])
    {
        return ApiResponse::validationError($message, $errors);
    }
}

if (!function_exists('api_not_found')) {
    function api_not_found(string $message = 'Resource not found')
    {
        return ApiResponse::notFound($message);
    }
}

if (!function_exists('api_unauthorized')) {
    function api_unauthorized(string $message = 'Unauthorized')
    {
        return ApiResponse::unauthorized($message);
    }
}

if (!function_exists('api_forbidden')) {
    function api_forbidden(string $message = 'Forbidden')
    {
        return ApiResponse::forbidden($message);
    }
}

if (!function_exists('api_created')) {
    function api_created($data = null, string $message = 'Resource created successfully')
    {
        return ApiResponse::created($data, $message);
    }
}
