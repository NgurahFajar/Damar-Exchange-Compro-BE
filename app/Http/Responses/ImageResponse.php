<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ImageResponse extends BaseResponse
{
    // Success response
    public static function success($data = null, ?string $messageKey = null, int $code = 200): JsonResponse
    {
        return self::send(
            self::formatResponse('success', $messageKey ?? 'images.success.operation', $data),
            $code
        );
    }

    // Error response for general use
    public static function error(string $messageKey, int $code = 400): JsonResponse
    {
        return self::send(
            self::formatResponse('error', $messageKey),
            $code
        );
    }

    // Response for '422 Unprocessable Entity' - Validation errors
    public static function unprocessableEntity(string $messageKey): JsonResponse
    {
        return self::send(
            self::formatResponse('error', $messageKey),
            422
        );
    }

    // Created success response
    public static function created($data): JsonResponse
    {
        return self::success($data, 'images.success.created', 201);
    }
}
