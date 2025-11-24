<?php

namespace App\Handlers\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Exceptions\Auth\AuthException;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Auth\AuthResponse;
use Throwable;

class AuthErrorHandler
{
    /**
     * Handle authentication related errors
     */
    public function handle(Throwable $e, array $context = []): JsonResponse
    {
        // Log the error with context
        $this->logError($e, $context);

        // Handle specific auth exceptions
        if ($e instanceof AuthException) {
            return $this->handleAuthException($e);
        }

        // Handle any other unexpected errors
        return $this->handleGenericException($e);
    }

    /**
     * Handle authentication specific exceptions
     */
    private function handleAuthException(AuthException $e): JsonResponse
    {
        $errorData = $e->getData() ?? [];

        return match ($e->getCode()) {
            401 => $this->handleUnauthorized($e),
            403 => AuthResponse::unauthorized($e->getMessage()),
            422 => AuthResponse::validationError($e->getMessage()),
            429 => $this->handleRateLimit($e),
            500 => $this->handleSystemError($e),
            default => AuthResponse::error(
                $e->getMessage() ?: 'auth.errors.system.server_error',
                $e->getCode() ?: 500,
                $errorData
            )
        };
    }

    /**
     * Handle unauthorized (401) errors
     */
    private function handleUnauthorized(AuthException $e): JsonResponse
    {
        $message = $e->getMessage();

        if (str_contains($message, 'session_expired')) {
            return AuthResponse::sessionExpired();
        }

        if (str_contains($message, 'invalid_token')) {
            return AuthResponse::error('auth.errors.validation.invalid_token', 401);
        }

        return AuthResponse::unauthenticated($message);
    }

    /**
     * Handle rate limiting (429) errors
     */
    private function handleRateLimit(AuthException $e): JsonResponse
    {
        $data = $e->getData();
        $seconds = $data['seconds'] ?? 0;

        return AuthResponse::tooManyAttempts(ceil($seconds / 60));
    }

    /**
     * Handle system (500) errors
     */
    private function handleSystemError(AuthException $e): JsonResponse
    {
        if (app()->environment('production')) {
            return AuthResponse::error('auth.errors.system.server_error', 500);
        }

        return AuthResponse::error($e->getMessage(), 500, [
            'trace' => $e->getTraceAsString()
        ]);
    }

    /**
     * Handle generic exceptions
     */
    private function handleGenericException(Throwable $e): JsonResponse
    {
        $errorMessage = app()->environment('production')
            ? 'auth.errors.system.server_error'
            : $e->getMessage();

        return AuthResponse::error($errorMessage, 500);
    }

    /**
     * Log the error with appropriate context
     */
    private function logError(Throwable $e, array $context = []): void
    {
        // Generate a unique error ID
        $errorId = Str::uuid()->toString();

        $logContext = array_merge([
            'error_id' => $errorId,
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'timestamp' => now()->toDateTimeString()
        ], $context);

        // Determine log level based on error type and code
        $level = match(true) {
            $e instanceof AuthException && $e->getCode() === 401 => 'warning',
            $e instanceof AuthException && $e->getCode() === 429 => 'warning',
            $e instanceof AuthException && $e->getCode() >= 500 => 'error',
            $e instanceof AuthException => 'info',
            default => 'error'
        };

        Log::log($level, 'Authentication error occurred', $logContext);
    }
}
