<?php
namespace App\Exceptions\Currency;

use Exception;

class CurrencyException extends Exception
{
    protected $data;

    public function __construct(string $message = "", int $code = 0, ?array $data = null, ?\Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public static function validationError(string $field, string $type = 'required'): self
    {
        return new self(
            __("currency.errors.validation.{$type}.{$field}"),
            422,
            ['field' => $field]
        );
    }

    public static function notFound(): self
    {
        return new self(
            __('currency.errors.not_found'),
            404
        );
    }

    public static function unauthorized(): self
    {
        return new self(
            __('currency.errors.unauthorized'),
            403
        );
    }

    public static function createFailed(\Throwable $previous = null): self
    {
        return new self(
            __('currency.errors.create_failed'),
            500,
            null,
            $previous
        );
    }

    public static function updateFailed(\Throwable $previous = null): self
    {
        return new self(
            __('currency.errors.update_failed'),
            500,
            null,
            $previous
        );
    }

    public static function deleteFailed(\Throwable $previous = null): self
    {
        return new self(
            __('currency.errors.delete_failed'),
            500,
            null,
            $previous
        );
    }

    public static function systemError(\Throwable $previous = null): self
    {
        return new self(
            __('currency.errors.system_error'),
            500,
            null,
            $previous
        );
    }

    public static function restoreFailed(\Throwable $previous = null): self
    {
        return new self(
            __('currency.errors.restore_failed'),
            500,
            null,
            $previous
        );
    }
}
