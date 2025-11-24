<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Responses\Auth\AuthResponse;
use Illuminate\Support\Facades\Log;

class CheckTokenExpiration
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = $request->user();

            // Debug logging
            Log::debug('Token check - Request info', [
                'hasBearer' => $request->hasHeader('Authorization'),
                'bearer' => $request->bearerToken(),
                'userFound' => !is_null($user),
                'token_expiration_env' => env('SANCTUM_TOKEN_EXPIRATION'),
                'token_expiration_config' => config('sanctum.expiration'),
                'current_time' => now()->toDateTimeString(),
            ]);

            if (!$user) {
                return AuthResponse::error('auth.errors.token.missing', 401);
            }

            $token = $user->currentAccessToken();

            if (!$token) {
                Log::warning('No current token found for authenticated user', [
                    'user_id' => $user->user_id
                ]);
                return AuthResponse::error('auth.errors.token.invalid', 401);
            }

            // Use Sanctum's configuration instead
            $tokenExpiration = (int) config('sanctum.expiration', 1440);

            Log::debug('Token check - Expiration details', [
                'token_created' => $token->created_at->toDateTimeString(),
                'expires_at' => $token->created_at->addMinutes($tokenExpiration)->toDateTimeString(),
                'current_time' => now()->toDateTimeString(),
                'expiration_minutes' => $tokenExpiration,
                'minutes_until_expiry' => now()->diffInMinutes($token->created_at->addMinutes($tokenExpiration), false)
            ]);

            if ($token->created_at->addMinutes($tokenExpiration) < now()) {
                $token->delete();
                Log::info('Token expired and deleted', [
                    'user_id' => $user->user_id,
                    'token_created_at' => $token->created_at
                ]);
                return AuthResponse::error('auth.errors.token.expired', 401);
            }

            return $next($request);

        } catch (\Exception $e) {
            Log::error('Token check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return AuthResponse::error('auth.errors.token.validation_failed', 401);
        }
    }
}
