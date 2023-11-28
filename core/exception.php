<?php

namespace app\core\exception;

class ApplicationException extends \Exception {}

class QueryBuilderException extends \Exception
{
    public static function InvalidMethod(): self
    {
        return new QueryBuilderException("Method is not valid");
    }

    public static function InvalidType(): self
    {
        return new QueryBuilderException("Invalid type");
    }

    public static function UnsupportedType(): self
    {
        return new QueryBuilderException("Unsupported type");
    }

    public static function EmptyValues(): self
    {
        return new QueryBuilderException("Values will not be empty!");
    }
}