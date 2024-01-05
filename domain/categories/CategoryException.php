<?php
namespace domain\categories;

use app\core\exception\ApplicationException;

class CategoryException extends ApplicationException
{
    public const NOT_FOUND = 404;
    public static function InvalidQuery(): self
    {
        return new CategoryException("Invalid utils");
    }
    public static function IncorrectFormat(): CategoryException
    {
        return new CategoryException("Incorrect value format");
    }
    public static function NotExecuted():self
    {
        return new CategoryException("Query not executed");
    }
    public static function NotFound(): self
    {
        return new CategoryException("Category not found", self::NOT_FOUND);
    }
}