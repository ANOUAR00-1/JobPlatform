<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    protected $statusCode;
    protected $errorCode;
    protected $details;

    public function __construct(
        string $message,
        string $errorCode = 'API_ERROR',
        int $statusCode = 500,
        array $details = []
    ) {
        parent::__construct($message);
        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
        $this->details = $details;
    }

    public function render(): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => [
                'code' => $this->errorCode,
                'message' => $this->message,
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        if (!empty($this->details) && config('app.debug')) {
            $response['error']['details'] = $this->details;
        }

        return response()->json($response, $this->statusCode);
    }

    // Factory methods for common errors
    public static function validation(string $message, array $details = []): self
    {
        return new self($message, 'VALIDATION_ERROR', 422, $details);
    }

    public static function notFound(string $message = 'Resource not found'): self
    {
        return new self($message, 'NOT_FOUND', 404);
    }

    public static function unauthorized(string $message = 'Unauthorized'): self
    {
        return new self($message, 'UNAUTHORIZED', 401);
    }

    public static function forbidden(string $message = 'Forbidden'): self
    {
        return new self($message, 'FORBIDDEN', 403);
    }

    public static function serverError(string $message = 'Internal server error'): self
    {
        return new self($message, 'SERVER_ERROR', 500);
    }

    public static function tooManyRequests(string $message = 'Too many requests'): self
    {
        return new self($message, 'RATE_LIMIT_EXCEEDED', 429);
    }
}
