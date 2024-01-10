<?php

namespace domain\users;

use app\core\exception\ApplicationException;

class UserException extends ApplicationException
{
    /**
     * Return new exception object
     * @return self
     */
    public static function InvalidQuery(): self
    {
        return new UserException("Invalid utils");
    }

    public static function NotExecuted(array $errors = null): self
    {
        return new UserException(implode(";\n", $errors));
    }

    public static function NotFound(string $message = ""): self
    {
        return new UserException(empty($message) ? "Not found" : $message, 404);
    }

    public static function Forbidden(string $message = ""): self
    {
        return new UserException(empty($message) ? "Permission denied" : $message, 403);
    }

    public static function Conflict(string $message = ""): self
    {
        return new UserException(empty($message) ? "Conflict" : $message, 409);
    }

    public static function BadRequest(string $message = ""): self
    {
        return new UserException(empty($message) ? "Bad request" : $message, 400);
    }
}