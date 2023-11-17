<?php

namespace app\core\orm\exceptions;

class ExceptionModel extends \Exception
{
    public static function AlreadyExists()
    {
        return new ExceptionModel("Model already exists");
    }
    public static function NotFound()
    {
        return new ExceptionModel("Model not found");
    }
}