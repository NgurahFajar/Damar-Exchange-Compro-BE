<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\Auth\AuthException;
use App\Exceptions\Currency\CurrencyException;
use App\Http\Responses\Auth\AuthResponse;
use App\Http\Responses\CurrencyResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Handle Authentication Exceptions (401 Unauthenticated)
        $this->renderable(function (AuthenticationException $e, $request) {
            Log::debug('Authentication Exception', [
                'message' => $e->getMessage(),
                'path' => $request->path(),
                'method' => $request->method()
            ]);

            return $this->unauthenticated($request, $e);
        });

        // Handle Auth Exceptions
        $this->renderable(function (AuthException $e, $request) {
            Log::debug('Auth Exception', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'data' => $e->getData()
            ]);

            return AuthResponse::error(
                $e->getMessage(),
                $e->getCode() ?: 400,
                $e->getData() ?? []
            );
        });

        // Handle Currency Exceptions
        $this->renderable(function (CurrencyException $e, $request) {
            Log::debug('Currency Exception', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'data' => $e->getData()
            ]);

            return CurrencyResponse::error(
                $e->getMessage(),
                $e->getCode() ?: 400,
                $e->getData() ?? []
            );
        });

        // Handle Validation Exceptions (422 Unprocessable Entity)
        $this->renderable(function (ValidationException $e, $request) {
            Log::debug('Validation Exception', [
                'errors' => $e->errors(),
                'path' => $request->path()
            ]);

            if ($request->expectsJson()) {
                $firstError = array_values($e->errors())[0][0] ?? null;

                if (str_contains($request->path(), 'currencies')) {
                    return CurrencyResponse::error($firstError, 422);
                }
                return AuthResponse::error('auth.errors.validation.invalid_format', 422, $e->errors());
            }
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            // Handle Admin Middleware Exceptions (403 Forbidden)
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                Log::warning('Authorization Exception', [
                    'path' => $request->path(),
                    'user_id' => auth()->id()
                ]);
                if (str_contains($request->path(), 'currencies')) {
                    return CurrencyResponse::error('currency.errors.unauthorized', 403);
                }
                return AuthResponse::error('auth.errors.auth.unauthorized', 403);
            }

            // Handle Token Expiration
            if ($e instanceof AuthenticationException &&
                str_contains($e->getMessage(), 'token')) {
                return AuthResponse::error('auth.errors.auth.token_expired', 401);
            }
        }

        return parent::render($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            Log::debug('Unauthenticated request', [
                'path' => $request->path(),
                'message' => $exception->getMessage()
            ]);

            if (str_contains($request->path(), 'currencies')) {
                return CurrencyResponse::error('currency.errors.unauthorized', 401);
            }
            return AuthResponse::error('auth.errors.auth.unauthenticated', 401);
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
