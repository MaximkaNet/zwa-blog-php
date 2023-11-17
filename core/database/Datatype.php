<?php
namespace app\core\database;
class Datatype {
    public static function INTEGER (): string {
        return "INT";
    }
    public static function TEXT ():string {
        return "TEXT";
    }
    public static function MEDIUMTEXT():string{
        return "MEDIUMTEXT";
    }
    public static function STRING (int $length): string
    {
        if ($length > 65535 || $length < 1) trigger_error("String length must be less 65535 and more 0", E_CORE_ERROR);
        return "VARCHAR($length)";
    }
    public static function DATETIME (): string
    {
        return "DATETIME";
    }
    public static function CURRENT_TIMESTAMP (): string
    {
        return "CURRENT_TIMESTAMP";
    }
}