<?php

namespace app\core\exception;

class ApplicationException extends \Exception
{
    public static function BadQuery(string $message, int $code):self
    {
        return new self($message, $code);
    }
    public static function NotFound(): self
    {
        return new self("Not found", 404);
    }
}