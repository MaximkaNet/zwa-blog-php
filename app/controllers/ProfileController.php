<?php

namespace app\controllers;

use app\core\database\MysqlConfig;
use app\core\exception\ApplicationException;
use app\core\Router;
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
            $articles_repo = PostsRepository::init($pdo);

            $categories = $categories_repo->findAll();
            $user = $repo->findById($id);
            $user_articles = $articles_repo->findAll(["user_id" => $user->getId()]);

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