<?php

namespace App\Exceptions\Auth;

use Exception;
use Throwable;

class AuthException extends Exception
{
    protected ?array $data;

    public function __construct(string $message = "", int $code = 0, ?array $data = null, ?Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    // Validation Errors
    public static function validationError(string $field, string $type = 'required'): self
    {
        return new self(
            __("auth.errors.validation.{$type}"),
            422,
            ['field' => $field]
        );
    }

    public static function invalidCredentials(): self
    {
        return new self(
            __('auth.errors.auth.invalid_credentials'),
            401
        );
    }

    public static function unauthorized(): self
    {
        return new self(
            __('auth.errors.auth.unauthorized'),
            403
        );
    }

    public static function unauthenticated(): self
    {
        return new self(
            __('auth.errors.auth.unauthenticated'),
            401
        );
    }

    public static function tooManyAttempts(int $seconds): self
    {
        return new self(
            __('auth.errors.auth.too_many_attempts', [
                'time' => $seconds > 60
                    ? __('auth.time.minutes', ['count' => ceil($seconds / 60)])
                    : __('auth.time.seconds', ['count' => $seconds])
            ]),
            429,
            ['seconds' => $seconds]
        );
    }

    public static function accountLocked(int $minutes): self
    {
        return new self(
            __('auth.errors.auth.account_locked'),
            423,
            ['minutes' => $minutes]
        );
    }

    public static function sessionExpired(): self
    {
        return new self(
            __('auth.errors.auth.session_expired'),
            401,
            ['requires_reauth' => true]
        );
    }

    public static function invalidToken(?string $message = null): self
    {
        return new self(
            $message ?? __('auth.errors.auth.invalid_token'),
            401,
            ['requires_reauth' => true]
        );
    }

    public static function concurrentLogin(): self
    {
        return new self(
            __('auth.errors.auth.concurrent_login'),
            401,
            ['requires_reauth' => true]
        );
    }

    public static function suspiciousActivity(array $details = []): self
    {
        return new self(
            __('auth.errors.security.suspicious_activity'),
            403,
            array_merge($details, ['requires_verification' => true])
        );
    }

    public static function maintenanceMode(): self
    {
        return new self(
            __('auth.errors.system.maintenance'),
            503,
            ['retry_after' => 300] // 5 minutes
        );
    }

    public static function systemError(?Throwable $previous = null): self
    {
        return new self(
            __('auth.errors.system.server_error'),
            500,
            null,
            $previous
        );
    }

    public static function serviceUnavailable(): self
    {
        return new self(
            __('auth.errors.system.service_unavailable'),
            503
        );
    }
}
