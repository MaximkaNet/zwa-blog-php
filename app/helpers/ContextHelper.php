<?php

namespace app\helpers;

use app\core\Router;

class ContextHelper
{
    /**
     * Init all contexts
     * @return array
     */
    public static function initContext(): array
    {
        $head_context = self::headContext();
        $header_context = self::headerContext();
        $articles_context = self::articlesContext();
        $widgets_context = self::widgetsContext();
        $footer_context = self::footerContext();
        return [
            "head" => $head_context,
            "header" => $header_context,
            "articles" => $articles_context,
            "widgets" => $widgets_context,
            "footer" => $footer_context
        ];
    }

    /**
     * Head context
     * @return string[]
     */
    public static function headContext(): array
    {
        return [
            "title" => "All posts"
        ];
    }

    /**
     * Header context
     * @return array
     */
    public static function headerContext(): array
    {
        $header_context = [
            "nav" => [
                "items" => []
            ],
            "logo" => [
                "image" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"]),
                "link" => Router::link("/category/articles", $_ENV["URL_PREFIX"])
            ]
        ];
        // Check auth status
        $authorized = $_SESSION["user"]["is_auth"] ?? false;
        $header_context["auth"] = self::authHeaderLinks($authorized);
        return $header_context;
    }

    /**
     * Articles context
     * @return array
     */
    public static function articlesContext(): array
    {
        return [
            "items" => [],
            "pagination" => [
                "items" => []
            ]
        ];
    }

    /**
     * Widgets context
     * @return array
     */
    public static function widgetsContext(): array
    {
        return [];
    }

    /**
     * Footer context
     * @return array
     */
    public static function footerContext(): array
    {
        $footer_context =  [
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
        $footer_context["sections"]["socials"] = self::socialsContext();

        return $footer_context;
    }

    /**
     * Context to single article
     * @return array
     */
    public static function singleContext(): array
    {
        return [];
    }

    /**
     * Context for footer social links
     * @return array
     */
    public static function socialsContext(): array
    {
        $socials = [
            "instagram" => "#",
            "youtube" => "#",
            "twitter" => "#",
            "telegram" => "#"
        ];
        $socials_context = [];
        foreach ($socials as $social => $link) {
            $socials_context[] = [
                "link" => $link,
                "image" => [
                    "src" => Router::link("/assets/images/socials/$social.png", $_ENV["URL_PREFIX"]),
                    "alter" => $social
                ]
            ];
        }
        return $socials_context;
    }

    /**
     * Context for header links
     * @param bool $authorized
     * @return array[]
     */
    public static function authHeaderLinks(bool $authorized = false): array
    {
        if ($authorized) {
            return [
                "authorized" => [
                    "admin_link" => Router::link("/admin", $_ENV["URL_PREFIX"])
                ]
            ];
        }
        return [
            "unauthorized" => [
                "login_link" => Router::link("/login", $_ENV["URL_PREFIX"]),
                "signup_link" => Router::link("/signup", $_ENV["URL_PREFIX"]),
            ]
        ];
    }

    public static function adminContext(): array
    {
        $context = [];

        $context["menu"] = [
            "logo" => [
                "link" => Router::link("/admin", $_ENV["URL_PREFIX"]),
                "src" => Router::link("/assets/images/logo-admin.svg", $_ENV["URL_PREFIX"]),
            ],
            "links" => [
                "posts" => Router::link("/admin", $_ENV["URL_PREFIX"]),
                "profile" => Router::link("/admin/profile", $_ENV["URL_PREFIX"]),
                "home" => Router::link("/", $_ENV["URL_PREFIX"]),
                "logout" => Router::link("/logout", $_ENV["URL_PREFIX"]),
            ]
        ];

        return $context;
    }

    public static function adminHeaderContext(): array
    {
        $context = [];

        return $context;
    }

    public static function adminBodyContext(): array
    {
        $context = [];

        return $context;
    }
}