<?php

namespace app\controllers\api;

class PostsAPIController
{
    public static function create(): void
    {
        // Check user authorized and role
        header("Content-Type: application/json");
        echo "{\"message\": \"Hello\"}";
    }

    public static function edit(int $id): void
    {

    }

    public static function like(): void
    {

    }

    public static function save(): void
    {

    }
}