<?php

namespace App\Exceptions\Image;

class ImageException extends \Exception
{
    public static function maxImagesReached(): self
    {
        return new self('images.errors.max_limit_reached', 422);
    }

    public static function notFound(): self
    {
        return new self('images.errors.not_found', 404);
    }

    public static function uploadFailed(\Exception $e): self
    {
        return new self('images.errors.upload_failed', 500);
    }
}
