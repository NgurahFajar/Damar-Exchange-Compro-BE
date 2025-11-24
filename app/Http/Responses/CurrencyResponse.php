<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CurrencyResponse extends BaseResponse
{
    public static function success($data = null, ?string $messageKey = null, int $code = 200): JsonResponse
    {
        Log::debug('Currency success response', [
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
        Log::debug('Currency error response', [
            'message_key' => $messageKey,
            'code' => $code,
            'params' => $params
        ]);

        return self::send(
            self::formatResponse('error', $messageKey, null, $params ?? []),
            $code
        );
    }

    public static function validationError(string $field, string $type = 'required'): JsonResponse
    {
        $messageKey = "currency.errors.validation.{$type}.{$field}";
        return self::error($messageKey, 422, ['field' => $field]);
    }

    public static function notFound(): JsonResponse
    {
        return self::error('currency.errors.not_found', 404);
    }

    public static function unauthorized(): JsonResponse
    {
        return self::error('currency.errors.unauthorized', 403);
    }

    public static function createFailed(): JsonResponse
    {
        return self::error('currency.errors.create_failed', 500);
    }

    public static function updateFailed(): JsonResponse
    {
        return self::error('currency.errors.update_failed', 500);
    }

    public static function deleteFailed(): JsonResponse
    {
        return self::error('currency.errors.delete_failed', 500);
    }

    public static function methodNotAllowed(string $method): JsonResponse
    {
        return self::error(
            'currency.method.not_allowed',
            405,
            ['method' => $method]
        );
    }

    public static function iconError(string $type): JsonResponse
    {
        return self::error("currency.errors.validation.icon.{$type}", 422);
    }

    public static function restored($currency): JsonResponse
    {
        return self::success(
            $currency,
            'currency.success.restored',
            200
        );
    }

    public static function created($currency): JsonResponse
    {
        return self::success(
            $currency,
            'currency.success.created',
            201
        );
    }

    public static function restoreFailed(): JsonResponse
    {
        return self::error('currency.errors.restore_failed', 500);
    }
}
