<?php

namespace app\controllers;

use app\core\Router;

class AdminController
{
    /**
     * @return void
     */
    public static function dashboard(): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }
        $context = [
            "head" => [
                "title" => "Dashboard"
            ],
            "header" => [
                "auth" => [],
                "nav" => [
                    "items" => []
                ],
                "logo" => [
                    "image" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"]),
                    "link" => Router::link("/category/articles", $_ENV["URL_PREFIX"])
                ]
            ],
            "articles" => [
                "items" => [],
                "pagination" => [
                    "items" => []
                ]
            ],
            "widgets" => [],
            "footer" => [],
        ];
        return ["template" => "admin/admin", "context" => $context];
    }

    public static function createArticle(): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }
        echo "Create article";
    }

    public static function editArticle(): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }
        echo "Edit article";
    }

    public static function settings(): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }
        $context = [
            "head" => [
                "title" => "Settings"
            ],
            "header" => [
                "auth" => [],
                "nav" => [
                    "items" => []
                ],
                "logo" => [
                    "image" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"]),
                    "link" => Router::link("/category/articles", $_ENV["URL_PREFIX"])
                ]
            ],
            "articles" => [
                "items" => [],
                "pagination" => [
                    "items" => []
                ]
            ],
            "widgets" => [],
            "footer" => [],
        ];
        return ["template" => "admin/settings", "context" => $context];
    }
}