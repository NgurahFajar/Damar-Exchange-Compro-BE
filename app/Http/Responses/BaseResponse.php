<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

abstract class BaseResponse
{
    protected static function formatResponse(
        string $status,
        ?string $messageKey = null,
        $data = null,
        array $params = []
    ): array {
        $response = ['status' => $status];

        if ($messageKey) {
            try {
                if (trans()->has($messageKey)) {
                    $translatedMessage = __($messageKey, $params);
                    Log::debug('Translation successful', [
                        'key' => $messageKey,
                        'result' => $translatedMessage,
                        'params' => $params
                    ]);
                    $response['message'] = $translatedMessage;
                } else {
                    Log::warning('Translation key not found', [
                        'key' => $messageKey,
                        'params' => $params
                    ]);
                    // Use the message key as fallback
                    $response['message'] = $messageKey;
                }
            } catch (\Exception $e) {
                Log::error('Translation failed', [
                    'key' => $messageKey,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $response['message'] = $messageKey;
            }
        }

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return $response;
    }

    protected static function send(array $response, int $code): JsonResponse
    {
        return response()->json($response, $code);
    }
}
