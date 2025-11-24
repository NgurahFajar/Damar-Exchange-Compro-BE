<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Responses\Auth\AuthResponse;
use App\Exceptions\Auth\AuthException;
use App\Handlers\Auth\AuthErrorHandler;
use App\Models\User;
use App\Services\Auth\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;
    private const DECAY_MINUTES = 15;

    public function __construct(
        private TokenService $tokenService,
        private AuthErrorHandler $errorHandler
    ) {}

    public function login(AuthRequest $request)
    {
        try {
            // Check rate limiting
            if ($this->checkTooManyAttempts($request)) {
                $rateLimitInfo = $this->getRemainingAttempts($request);
                return AuthResponse::tooManyAttempts(
                    ceil($rateLimitInfo['available_in'] / 60),
                    $rateLimitInfo
                );
            }

            // Single database query with select of needed fields
            $user = User::select(['user_id', 'user', 'password'])
                ->where('user', $request->user)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                $this->incrementAttempts($request);
                $rateLimitInfo = $this->getRemainingAttempts($request);
                return AuthResponse::error(
                    'auth.errors.invalid_credentials',
                    401,
                    ['remaining_attempts' => $rateLimitInfo['remaining']]
                );
            }

            // Clear attempts on successful login
            $this->clearLoginAttempts($request);

            $tokens = $this->tokenService->createUserTokens(
                $user,
                $request->boolean('remember_me', false)
            );

            return AuthResponse::success([
                'user' => [
                    'user_id' => $user->user_id,
                    'user' => $user->user
                ],
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'expires_in' => $tokens['expires_in'],
                'token_type' => 'Bearer'
            ]);

        } catch (AuthException $e) {
            return $this->errorHandler->handle($e);
        }
    }

    public function user(Request $request)
    {
        $user = auth()->user();

        return AuthResponse::success([
            'user' => [
                'user_id' => $user->user_id,
                'user' => $user->user,
                'role' => $user->role,
            ]
        ]);
    }

    private function throttleKey(AuthRequest $request): string
    {
        return Str::lower($request->input('user')) . '|' . $request->ip();
    }

    private function checkTooManyAttempts(AuthRequest $request): bool
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey($request),
            self::MAX_ATTEMPTS
        );
    }

    private function incrementAttempts(AuthRequest $request): void
    {
        RateLimiter::hit(
            $this->throttleKey($request),
            self::DECAY_MINUTES * 60 // Convert minutes to seconds
        );
    }

    private function clearLoginAttempts(AuthRequest $request): void
    {
        RateLimiter::clear($this->throttleKey($request));
    }

    private function getRemainingAttempts(AuthRequest $request): array {
        $key = $this->throttleKey($request);
        $attempts = RateLimiter::attempts($key);
        $remaining = RateLimiter::remaining($key, self::MAX_ATTEMPTS);
        $availableIn = RateLimiter::availableIn($key);

        return [
            'attempts' => $attempts,
            'remaining' => $remaining,
            'available_in' => $availableIn
        ];
    }
}
