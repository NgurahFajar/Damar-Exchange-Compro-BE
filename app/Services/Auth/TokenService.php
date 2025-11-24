<?php

namespace App\Services\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class TokenService
{
    private const ACCESS_TOKEN_LIFETIME = 30;
    private const REFRESH_TOKEN_LIFETIME = 1440;
    private const CACHE_PREFIX = 'user_session:';

    public function createUserTokens(User $user, bool $rememberMe = false): array
    {
        $this->revokeExpiredTokens($user);

        $sessionId = Str::random(40);
        $tokenPrefix = Config::get('auth_settings.token.prefix', 'token_');

        $accessExpiry = $this->getTokenExpiry($rememberMe, true);
        $refreshExpiry = $this->getTokenExpiry($rememberMe, false);

        $tokens = $this->generateTokens(
            $user,
            $sessionId,
            $tokenPrefix,
            $accessExpiry,
            $refreshExpiry,
            $rememberMe
        );

        $this->storeSessionInfo($user, $sessionId, $accessExpiry);

        return $tokens;
    }

    private function getTokenExpiry(bool $rememberMe, bool $isAccessToken): Carbon
    {
        if ($isAccessToken) {
            return now()->addMinutes(self::ACCESS_TOKEN_LIFETIME);
        }

        return $rememberMe
            ? now()->addDays(30)
            : now()->addMinutes(self::REFRESH_TOKEN_LIFETIME);
    }

    private function generateTokens(
        User $user,
        string $sessionId,
        string $prefix,
        Carbon $accessExpiry,
        Carbon $refreshExpiry,
        bool $rememberMe
    ): array {
        $suffix = $rememberMe ? '_remember' : '';

        $accessToken = $user->createToken(
            "{$prefix}access_{$sessionId}{$suffix}",
            ['*'],
            $accessExpiry
        );

        $refreshToken = $user->createToken(
            "{$prefix}refresh_{$sessionId}{$suffix}",
            ['*'],
            $refreshExpiry
        );

        return [
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'expires_in' => self::ACCESS_TOKEN_LIFETIME * 60,
            'token_type' => 'Bearer'
        ];
    }

    private function storeSessionInfo(User $user, string $sessionId, Carbon $expiry): void
    {
        $sessionData = [
            'last_activity' => now(),
            'expires_at' => $expiry,
            'user_agent' => request()->userAgent(),
            'ip' => request()->ip()
        ];

        Cache::put(
            self::CACHE_PREFIX . "{$user->user_id}:{$sessionId}",
            $sessionData,
            $expiry
        );
    }

    private function revokeExpiredTokens(User $user): void
    {
        $user->tokens()
            ->where('expires_at', '<', now())
            ->delete();
    }

    public function isTokenExpired(string $token): bool
    {
        try {
            [$id, $tokenHash] = explode('|', $token, 2);

            return !User::query()
                ->whereHas('tokens', function ($query) use ($tokenHash) {
                    $query->where('token', hash('sha256', $tokenHash))
                        ->where('expires_at', '>', now());
                })
                ->exists();
        } catch (\Exception) {
            return true;
        }
    }

    public function revokeUserTokens(User $user): void
    {
        $user->tokens()->delete();

        $pattern = self::CACHE_PREFIX . $user->user_id . ':*';
        foreach (Cache::get($pattern, []) as $key) {
            Cache::forget($key);
        }
    }
}
