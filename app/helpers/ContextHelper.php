<?php

namespace app\helpers;

use app\core\Router;

class ContextHelper
{
    public static function initContext(): array
    {
        $head_context = [
            "title" => "All posts"
        ];
        $header_context = [
            "nav" => [
                "items" => []
            ],
            "logo" => [
                "image" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"]),
                "link" => Router::link("/category/articles", $_ENV["URL_PREFIX"])
            ]
        ];
        $articles_context = [
            "items" => [],
            "pagination" => [
                "items" => []
            ]
        ];
        $widgets_context = [];
        $footer_context = [
            "copyright" => "CodeHub | 2024",
            "sections" => [
                "about" => [
                    "logo" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"]),
                    "content" => "This website was created for the subject of web application fundamentals"
                ],
                "categories" => [
                    "title" => "Categories",
                    "items" => []
                ],
                "socials" => []
            ],
        ];
        $socials = [
            "instagram" => "#",
            "youtube" => "#",
            "twitter" => "#",
            "telegram" => "#"
        ];
        foreach ($socials as $social => $link) {
            $footer_context["sections"]["socials"][] = [
                "link" => $link,
                "image" => [
                    "src" => Router::link("/assets/images/socials/$social.png", $_ENV["URL_PREFIX"]),
                    "alter" => $social
                ]
            ];
        }
        // Check auth status
        $authorized = $_SESSION["user"]["is_auth"] ?? false;
        if ($authorized) {
            $header_context["auth"] = [
                "authorized" => [
                    "admin_link" => Router::link("/admin", $_ENV["URL_PREFIX"])
                ]
            ];
        } else {
            $header_context["auth"] = [
                "unauthorized" => [
                    "login_link" => Router::link("/login", $_ENV["URL_PREFIX"]),
                    "signup_link" => Router::link("/signup", $_ENV["URL_PREFIX"]),
                ]
            ];
        }
        return [
            "head" => $head_context,
            "header" => $header_context,
            "articles" => $articles_context,
            "widgets" => $widgets_context,
            "footer" => $footer_context
        ];
    }
}