<?php

namespace app\controllers;

use app\core\database\MysqlConfig;
use app\core\exception\ApplicationException;
use app\core\Router;
use app\core\utils\pagination\Paginator;
use app\helpers\ContextHelper;
use domain\categories\CategoriesRepository;
use domain\posts\PostsRepository;

class ArticlesController
{
    /**
     * Redirect to /category/articles
     * @return array
     */
    public static function redirectToAll(): array
    {
        header("Location: " . Router::link("/category/articles", $_ENV["URL_PREFIX"]));
        return [];
    }

    /**
     * Redirect to /category/:name/articles
     * @param string $category
     * @return array
     */
    public static function redirectToCategory(string $category): array
    {
        header("Location: " . Router::link("/category/$category/articles", $_ENV["URL_PREFIX"]));
        return [];
    }

    /**
     * Home page controller
     * @param string|null $page
     * @return array
     * @throws ApplicationException
     */
    public static function all(string $page = null):array
    {
        try {
            $context = ContextHelper::initContext();
            // Context parts
            $head_context = $context["head"];
            $header_context = $context["header"];
            $articles_context = $context["articles"];
            $widgets_context = $context["widgets"];
            $footer_context = $context["footer"];
            // Base context structure
            $head_context["title"] = "All posts";
            // Setup configuration
            $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
            $pdo = $config->getPDO();
            // Initialize repositories
            $posts_repo = PostsRepository::init($pdo);
            $categories_repo = CategoriesRepository::init($pdo);
            // Get categories
            $categories = $categories_repo->findAll();
            // Add navigation items
            if (empty($categories)) {
                $header_context["nav"]["items"][] = [
                    "link" => Router::link("/category/articles", $_ENV["URL_PREFIX"]),
                    "display_name" => "All",
                    "current" => true
                ];
                $footer_context["sections"]["categories"]["items"][] = [
                    "link" => Router::link("/category/articles", $_ENV["URL_PREFIX"]),
                    "display_name" => "All",
                ];
            } else {
                foreach ($categories as $category) {
                    $header_context["nav"]["items"][] = [
                        "link" => Router::link(
                            "/category/" . $category->getName() . "/articles",
                            $_ENV["URL_PREFIX"]
                        ),
                        "display_name" => $category->getDisplayName(),
                        "current" => empty($category->getName())
                    ];
                    $footer_context["sections"]["categories"]["items"][] = [
                        "link" => Router::link(
                            "/category/" . $category->getName() . "/articles",
                            $_ENV["URL_PREFIX"]
                        ),
                        "display_name" => $category->getDisplayName(),
                    ];
                }
            }
            // Get number of posts
            $count_posts = $posts_repo->count();
            // Setup paginator
            $paginator = new Paginator($count_posts);
            $paginator->setPageNumberItems(10);
            $paginator->resolve(isset($page) ? htmlspecialchars($page) : null);
            // Current page number
            $current_page = $paginator->getCurrentPage();
            // Create pagination items
            $articles_context["pagination"]["items"] = $paginator->generate(
                Router::link("/category/articles", $_ENV["URL_PREFIX"])
            );
            // Get posts for page
            $max_items_on_page = $paginator->getPageNumberItems();
            $posts = $posts_repo->findAll(null, [
                "limit" => [
                    "limit" => $max_items_on_page,
                    "offset" => ($current_page) * $max_items_on_page
                ]
            ]);
            // Set articles for page
            if (!empty($posts)) {
                $items = [];
                foreach ($posts as $post) {
                    $items[] = [
                        "id" => $post->getId(),
                        "title" => $post->getTitle(),
                        "content" => $post->getContent(),
                        "rating" => $post->getRating(),
                        "count_saved" => $post->getCountSaved(),
                        "user" => [
                            "id" => $post->getUser()->getId(),
                            "username" => $post->getUser()->getUserName(),
                            "full_name" => $post->getUser()->getFullName(),
                            "link" => Router::link("/users/" . $post->getUser()->getId(), $_ENV["URL_PREFIX"]),
                            "avatar" => Router::link("/static/users/avatar1.ico", $_ENV["URL_PREFIX"])
                        ],
                        "date" => $post->getCreationDateTime(),
                        "url" => Router::link("/articles/" . $post->getId(), $_ENV["URL_PREFIX"]),
                        "assets_links" => [
                            "like" => Router::link("/assets/images/like.svg", $_ENV["URL_PREFIX"]),
                            "save" => Router::link("/assets/images/save.svg", $_ENV["URL_PREFIX"])
                        ]
                    ];
                }
                $articles_context["items"] = $items;
            } else {
                $articles_context["items"] = [];
            }
            return [
                "template" => "index",
                "context" => [
                    "head" => $head_context,
                    "header" => $header_context,
                    "articles" => $articles_context,
                    "widgets" => $widgets_context,
                    "footer" => $footer_context,
                ]
            ];
        } catch (ApplicationException $exception) {
            throw $exception;
        }
    }

    /**
     * Category controller
     * @param string $category_name
     * @param string|null $page
     * @return array
     * @throws ApplicationException
     */
    public static function category(string $category_name, string $page = null): array
    {
        try {
            $context = ContextHelper::initContext();
            // Context parts
            $head_context = $context["head"];
            $header_context = $context["header"];
            $articles_context = $context["articles"];
            $widgets_context = $context["widgets"];
            $footer_context = $context["footer"];
            // Setup configuration
            $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
            $pdo = $config->getPDO();
            // Initialize repositories
            $posts_repo = PostsRepository::init($pdo);
            $categories_repo = CategoriesRepository::init($pdo);
            // Get categories
            $categories = $categories_repo->findAll();
            $current_category = htmlspecialchars($category_name);

            $current_category_id = null;
            // Add navigation items
            foreach ($categories as $category) {
                if ($category->getName() === $current_category) {
                    $current_category_id = $category->getId();
                    $head_context["title"] = $category->getDisplayName();
                }
                $header_context["nav"]["items"][] = [
                    "link" => Router::link("/category/" . $category->getName() . "/articles", $_ENV["URL_PREFIX"]),
                    "display_name" => $category->getDisplayName(),
                    "current" => $category->getName() === $current_category
                ];
                $footer_context["sections"]["categories"]["items"][] = [
                    "link" => Router::link("/category/" . $category->getName() . "/articles", $_ENV["URL_PREFIX"]),
                    "display_name" => $category->getDisplayName(),
                ];
            }
            // Get number of posts
            $count_posts = $posts_repo->count(["category_id" => $current_category_id]);

            // Setup paginator
            $paginator = new Paginator($count_posts);
            $paginator->setPageNumberItems(10);
            $paginator->resolve(isset($page) ? htmlspecialchars($page) : null);
            // Current page number
            $current_page = $paginator->getCurrentPage();
            // Create pagination items
            $articles_context["pagination"]["items"] = $paginator->generate(
                Router::link("/category/$current_category/articles", $_ENV["URL_PREFIX"])
            );
            // Get posts for page
            $max_items_on_page = $paginator->getPageNumberItems();
            $posts = $posts_repo->findAll([
                "categories" => [
                    "id" => $current_category_id
                ]
            ], [
                "limit" => [
                    "limit" => $max_items_on_page,
                    "offset" => $current_page * $max_items_on_page
                ]
            ]);

            // Set articles for page
            if (!empty($posts)) {
                $items = [];
                foreach ($posts as $post) {
                    $items[] = [
                        "id" => $post->getId(),
                        "title" => $post->getTitle(),
                        "content" => $post->getContent(),
                        "rating" => $post->getRating(),
                        "count_saved" => $post->getCountSaved(),
                        "user" => [
                            "id" => $post->getUser()->getId(),
                            "username" => $post->getUser()->getUserName(),
                            "full_name" => $post->getUser()->getFullName(),
                            "link" => Router::link("/users/" . $post->getUser()->getId(), $_ENV["URL_PREFIX"]),
                            "avatar" => Router::link("/static/users/avatar1.ico", $_ENV["URL_PREFIX"])
                        ],
                        "date" => $post->getCreationDateTime(),
                        "url" => Router::link("/articles/" . $post->getId()),
                        "assets_links" => [
                            "like" => Router::link("/assets/images/like.svg", $_ENV["URL_PREFIX"]),
                            "save" => Router::link("/assets/images/save.svg", $_ENV["URL_PREFIX"])
                        ]
                    ];
                }
                $articles_context["items"] = $items;
            } else {
                $articles_context["items"] = [];
            }
            return [
                "template" => "index",
                "context" => [
                    "head" => $head_context,
                    "header" => $header_context,
                    "articles" => $articles_context,
                    "widgets" => $widgets_context,
                    "footer" => $footer_context,
                ]
            ];
        } catch (ApplicationException $exception) {
            throw $exception;
        }
    }
}