<?php

namespace app\controllers;

use app\core\database\MysqlConfig;
use app\core\Router;
use app\helpers\ContextHelper;
use domain\categories\CategoriesRepository;
use domain\posts\PostsService;
use domain\users\UserRole;

class AdminController
{
    /**
     * @return void
     */
    public static function myPosts(): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }

        if($_SESSION["user"]["role"] === UserRole::USER){
            header("Location: " . Router::link("/admin/profile", $_ENV["URL_PREFIX"]));
            return [];
        }

        $head_context = ContextHelper::headContext();
        $head_context["title"] = "My posts";
        $admin_context = ContextHelper::adminContext();
        $header_context = ContextHelper::adminHeaderContext();

        $admin_context["menu"] = [
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

        $header_context["title"] = "My posts";
        $header_context["write_post_btn"] = [
            "link" => Router::link("/admin/article", $_ENV["URL_PREFIX"])
        ];

        $admin_context["header"] = $header_context;
        return ["template" => "admin/my_posts", "context" => [
            "head" => $head_context,
            "admin" => $admin_context
        ]];
    }

    public static function profile(): array
    {
        return [];
    }

    public static function createArticle(): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }
        // Create a new article
        $service = PostsService::get();
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $categories_repo = CategoriesRepository::init($config->getPDO());
        $category = $categories_repo->findOne(["name" => ""]);
        $created_id = $service->create("The title", "Nothing ...", $_SESSION["user"]["id"], $category->getId());
        // Redirect to edit page
        header("Location: " . Router::link("/admin/article/$created_id/edit", $_ENV["URL_PREFIX"]));
        return [];
    }

    public static function editArticle(int $article_id): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }
        $service = PostsService::get();
        $available_for_user = $service->checkArticleAccess($article_id, $_SESSION["user"]["id"]);
        if(!$available_for_user) {
            header("Location: " . Router::link("/admin", $_ENV["URL_PREFIX"]));
        }
        echo "Edit article";
        return [];
    }

    public static function previewArticle(int $article_id): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }
        return [];
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