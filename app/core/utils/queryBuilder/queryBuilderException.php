<?php

namespace app\core\utils\queryBuilder;

class QueryBuilderException extends \Exception
{
    public static function InvalidMethod(): self
    {
        return new self("Method is not valid");
    }

    public static function InvalidType(): self
    {
        return new self("Invalid type");
    }

    public static function UnsupportedType(): self
    {
        return new self("Unsupported type");
    }

    public static function EmptyValues(): self
    {
        return new self("Values will not be empty!");
    }
}