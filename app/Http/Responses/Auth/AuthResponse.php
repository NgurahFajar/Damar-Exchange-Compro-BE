<?php

namespace App\Http\Responses\Auth;

use App\Http\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthResponse extends BaseResponse
{
    public static function success($data = null, ?string $messageKey = null, int $code = 200): JsonResponse
    {
        $messageKey = $messageKey ?? 'auth.success.login';

        Log::debug('Auth success response', [
            'message_key' => $messageKey,
            'data' => $data,
            'code' => $code
        ]);

        return self::send(
            self::formatResponse('success', $messageKey, $data),
            $code
        );
    }

    public static function error(string $messageKey, int $code = 400, ?array $params = []): JsonResponse
    {
        $params = $params ?? [];

        Log::debug('Auth error response', [
            'message_key' => $messageKey,
            'code' => $code,
            'params' => $params
        ]);

        return self::send(
            self::formatResponse('error', $messageKey, null, $params),
            $code
        );
    }

    public static function unauthorized(string $messageKey = 'auth.errors.auth.unauthorized'): JsonResponse
    {
        return self::error($messageKey, 403);
    }

    public static function unauthenticated(string $messageKey = 'auth.errors.auth.unauthenticated'): JsonResponse
    {
        return self::error($messageKey, 401);
    }

    public static function validationError(string $messageKey = 'auth.errors.validation.required_fields'): JsonResponse
    {
        return self::error($messageKey, 422);
    }

    public static function tooManyAttempts(int $minutes, array $rateLimitInfo = []): JsonResponse
    {
        return self::error(
            'auth.errors.auth.too_many_attempts',
            429,
            [
                'minutes' => $minutes,
                'rate_limit_info' => $rateLimitInfo
            ]
        );
    }

    public static function info(string $messageKey, array $params = [], int $code = 200): JsonResponse
    {
        return self::send(
            self::formatResponse('info', $messageKey, null, $params),
            $code
        );
    }

    public static function sessionExpired(): JsonResponse
    {
        return self::error('auth.errors.auth.session_expired', 401);
    }

    public static function suspiciousActivity(): JsonResponse
    {
        return self::error('auth.errors.security.suspicious_activity', 403);
    }
}
