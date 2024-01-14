<?php

namespace app\controllers;

use app\core\database\MysqlConfig;
use app\core\Router;
use app\helpers\ContextHelper;
use domain\categories\CategoriesRepository;
use domain\categories\CategoriesService;
use domain\posts\PostsService;
use domain\users\UserRole;
use domain\users\UsersRepository;

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

        $head_context = ContextHelper::headContext();
        $head_context["title"] = "My posts";
        $admin_context = ContextHelper::adminContext();
        $header_context = ContextHelper::adminHeaderContext();
        $body_context = ContextHelper::adminBodyContext();

        $header_context["title"] = "My posts";
        if ($_SESSION["user"]["role"] === UserRole::ADMIN) {
            $header_context["write_post_btn"] = [
                "link" => Router::link("/admin/article", $_ENV["URL_PREFIX"])
            ];
        }
        $service = PostsService::get();
        $posts = $service->getAllForUser($_SESSION["user"]["id"]);
        if (isset($posts)) {
            foreach ($posts as $post) {
                $body_context["items"][] = [
                    "id" => $post->getId(),
                    "title" => $post->getTitle(),
                    "date" => $post->getCreationDateTime(),
                    "link" => Router::link("/articles/" . $post->getId(), $_ENV["URL_PREFIX"]),
                    "edit" => [
                        "link" => Router::link("/admin/article/" . $post->getId() . "/edit", $_ENV["URL_PREFIX"]),
                        "image" => [
                            "src" => Router::link("/assets/images/pen.png", $_ENV["URL_PREFIX"])
                        ]
                    ],
                    "delete" => [
                        "image" => [
                            "src" => Router::link("/assets/images/bin.png", $_ENV["URL_PREFIX"])
                        ]
                    ]
                ];
            }
        } else {
            if ($_SESSION["user"]["role"] === UserRole::USER) {
                header("Location: " . Router::link("/admin/profile", $_ENV["URL_PREFIX"]));
                return [];
            }
            $body_context["items"] = [];
        }

        $admin_context["header"] = $header_context;
        $admin_context["body"] = $body_context;
        $admin_context["scripts"] = [
            ["src" => Router::link("/assets/js/delete-post.js")]
        ];
        return [
            "template" => "admin/my_posts",
            "context" => [
                "head" => $head_context,
                "admin" => $admin_context
            ]
        ];
    }

    public static function profile(): array
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }

        $head_context = ContextHelper::headContext();
        $head_context["title"] = "Profile";
        $admin_context = ContextHelper::adminContext();
        $header_context = ContextHelper::adminHeaderContext();
        $body_context = ContextHelper::adminBodyContext();

        $header_context["title"] = "Profile";
        if ($_SESSION["user"]["role"] === UserRole::ADMIN) {
            $header_context["write_post_btn"] = [
                "link" => Router::link("/admin/article", $_ENV["URL_PREFIX"])
            ];
        }

        $body_context["images"] = [
            "edit" => [
                "src" => Router::link("/assets/images/pen.png", $_ENV["URL_PREFIX"])
            ]
        ];

        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $user_repo = UsersRepository::init($config->getPDO());
        $user = $user_repo->findById($_SESSION["user"]["id"]);
        // If user not found
        if (empty($user)) {
            $_SESSION["user"] = null;
            header("Location: " . Router::link("/login", $_ENV["URL_PREFIX"]));
            return [];
        }
        $body_context["info"] = [
            "id" => $user->getId(),
            "position" => htmlspecialchars($user->getRole()),
            "username" => htmlspecialchars($user->getUserName() ?? ""),
            "email" => htmlspecialchars($user->getEmail()),
            "first_name" => htmlspecialchars($user->getFirstName()),
            "last_name" => htmlspecialchars($user->getLastName()),
        ];
        $avatar = $user->getAvatar();
        if ($avatar) {
            $body_context["info"]["avatar"]["src"] = Router::link(
                "/static/users/" . htmlspecialchars($avatar),
                $_ENV["URL_PREFIX"]
            );
            $body_context["info"]["avatar"]["file_name"] = htmlspecialchars($avatar);
        } else {
            $body_context["info"]["avatar"]["src"] = Router::link(
                "/assets/images/default_avatar.png",
                $_ENV["URL_PREFIX"]
            );
            $body_context["info"]["avatar"]["file_name"] = "Avatar not uploaded yet";
        }

        $admin_context["header"] = $header_context;
        $admin_context["body"] = $body_context;

        $admin_context["scripts"] = [
            ["src" => Router::link("/assets/js/edit-profile.js", $_ENV["URL_PREFIX"])],
        ];
        return [
            "template" => "admin/profile",
            "context" => [
                "head" => $head_context,
                "admin" => $admin_context
            ]
        ];
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
        $candidate = $service->findEmpty();
        if (isset($candidate)) {
            $created_id = $candidate->getId();
        } else {
            $created_id = $service->create("The title", "", $_SESSION["user"]["id"], $category->getId());
        }
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
        $categories_service = CategoriesService::get();
        $available_for_user = $service->checkArticleAccess($article_id, $_SESSION["user"]["id"]);
        if (!$available_for_user) {
            header("Location: " . Router::link("/admin", $_ENV["URL_PREFIX"]));
        }

        // Context
        $head_context = ContextHelper::headContext();
        $head_context["title"] = "Edit post";
        $admin_context = ContextHelper::adminContext();
        $admin_header_context = ContextHelper::adminHeaderContext();
        $admin_body_context = ContextHelper::adminBodyContext();
        $admin_header_context["title"] = "Edit post";
        // Body context
        $post = $service->getOne($article_id);
        if (empty($post)) {
            $admin_body_context["title"] = "Nothing ...";
            $admin_body_context["content"] = "Empty content";
        } else {
            $admin_body_context["id"] = $post->getId();
            $admin_body_context["title"] = $post->getTitle();
            $admin_body_context["content"] = $post->getContent();
        }
        // init repo
        $all_categories = $categories_service->getAll();
        foreach ($all_categories as $category) {
            $admin_body_context["categories"][] = [
                "display_name" => $category->getDisplayName(),
                "id" => $category->getId(),
                "current" => ($category->getId() === $post->getCategory()->getId())
            ];
        }
        // ------
        $admin_context["body"] = $admin_body_context;
        $admin_context["header"] = $admin_header_context;
        $admin_context["scripts"] = [
            ["src" => Router::link("/assets/js/edit-post.js", $_ENV["URL_PREFIX"])]
        ];
        return [
            "template" => "admin/edit-article",
            "context" => [
                "head" => $head_context,
                "admin" => $admin_context,
            ]
        ];
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