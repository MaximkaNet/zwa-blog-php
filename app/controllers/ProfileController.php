<?php

namespace app\controllers;

use app\core\database\MysqlConfig;
use app\core\exception\ApplicationException;
use app\core\Router;
use app\core\utils\pagination\Paginator;
use app\helpers\ContextHelper;
use domain\categories\CategoriesRepository;
use domain\posts\PostsRepository;
use domain\users\UsersRepository;

class ProfileController
{
    /**
     * User profile controller
     * @param int $id
     * @param string|null $page
     * @return array
     * @throws ApplicationException
     */
    public static function withArticles(int $id, string $page = null): array
    {
        try {
            $context = ContextHelper::initContext();
            // Context parts
            $head_context = $context["head"];
            $header_context = $context["header"];
            $articles_context = $context["articles"];
            $widgets_context = $context["widgets"];
            $footer_context = $context["footer"];
            $head_context["title"] = "User profile";
            // Get user
            $mysql_conf = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
            $pdo = $mysql_conf->getPDO();
            $repo = UsersRepository::init($pdo);
            $categories_repo = CategoriesRepository::init($pdo);
            $posts_repo = PostsRepository::init($pdo);

            $categories = $categories_repo->findAll();
            $user = $repo->findById($id);

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

            $count_posts = $posts_repo->count(["user_id"=> $id]);
            // Setup paginator
            $paginator = new Paginator($count_posts);
            $paginator->setPageNumberItems(10);
            $paginator->resolve(isset($page) ? htmlspecialchars($page) : null);
            // Current page number
            $current_page = $paginator->getCurrentPage();
            // Create pagination items
            $articles_context["pagination"]["items"] = $paginator->generate(
                Router::link("/users/$id", $_ENV["URL_PREFIX"])
            );
            // Get posts for page
            $max_items_on_page = $paginator->getPageNumberItems();
            $user_articles = $posts_repo->findAll(["user_id" => $user->getId()], [
                "limit" => [
                    "limit" => $max_items_on_page,
                    "offset" => ($current_page) * $max_items_on_page
                ]
            ]);

            // Set articles for page
            if (!empty($user_articles)) {
                $items = [];
                foreach ($user_articles as $post) {
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

            $widgets_context["profile"] = [
                "role" => $user->getRole(),
                "username" => $user->getUserName(),
                "full_name" => $user->getFullName(),
                "signup_date" => $user->getDatetimeOfCreate(),
            ];
            $avatar = $user->getAvatar();
            if (isset($avatar)) {
                $widgets_context["profile"]["avatar"] = Router::link("/static/users/" . $avatar, $_ENV["URL_PREFIX"]);
            } else {
                $widgets_context["profile"]["avatar"] = Router::link(
                    "/assets/images/default_avatar.png",
                    $_ENV["URL_PREFIX"]
                );
            }

            return [
                "template" => "profile",
                "context" => [
                    "head" => $head_context,
                    "header" => $header_context,
                    "articles" => $articles_context,
                    "widgets" => $widgets_context,
                    "footer" => $footer_context,
                ]
            ];
        } catch (ApplicationException $e) {
            throw $e;
        }
    }
}