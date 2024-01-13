<?php

namespace app\helpers\validator;

class Validator
{
    /**
     * Email validator
     * @param string|null $value
     * @param bool $required
     * @return Result
     */
    public static function email(?string $value, bool $required = false): Result
    {
        $field_name = "Email";
        if ($required and empty("$value")) {
            return new Result(false, "$field_name field is required");
        }
        if (preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", "$value")) {
            return new Result(true, "$field_name is valid");
        }
        return new Result(false, "$field_name is not valid");
    }

    /**
     * Password validator
     * @param string|null $value
     * @param bool $required
     * @return Result
     */
    public static function password(?string $value, bool $required = false): Result
    {
        $field_name = "Password";
        if ($required and empty("$value")) {
            return new Result(false, "$field_name field is required");
        }
        if (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", "$value")) {
            return new Result(true, "$field_name is valid");
        }
        return new Result(
            false,
            "The password must contain capital letters and numbers or it is too short (minimum 8 characters)"
        );
    }

    /**
     * Password validator
     * @param string|null $value
     * @param bool $required
     * @return Result
     */
    public static function passwordConfirm(?string $value, bool $required = false): Result
    {
        $field_name = "Password confirm";
        if ($required and empty("$value")) {
            return new Result(false, "$field_name field is required");
        }
        if (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", "$value")) {
            return new Result(true, "$field_name is valid");
        }
        return new Result(
            false,
            "The password must contain capital letters and numbers or it is too short (minimum 8 characters)"
        );
    }

    public static function firstName(?string $value, bool $required = false): Result
    {
        $field_name = "First name";
        if ($required and empty("$value")) {
            return new Result(false, "$field_name field is required");
        }
        if (strlen("$value") > 15) {
            return new Result(false, "$field_name is too long");
        }
        if(preg_match("/\s+/", "$value")){
            return new Result(false, "Gaps in $field_name are not allowed");
        }
        return new Result(
            true,
            "$field_name is valid"
        );
    }

    public static function lastName(?string $value, bool $required = false): Result
    {
        $field_name = "Last name";
        if ($required and empty("$value")) {
            return new Result(false, "$field_name field is required");
        }
        if (strlen("$value") > 15) {
            return new Result(false, "$field_name is too long");
        }
        if(preg_match("/\s+/", "$value")){
            return new Result(false, "Gaps in $field_name are not allowed");
        }
        return new Result(
            true,
            "$field_name is valid"
        );
    }
}