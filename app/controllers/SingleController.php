<?php

namespace app\controllers;

use app\core\database\MysqlConfig;
use app\core\Router;
use app\helpers\ContextHelper;
use domain\categories\CategoriesRepository;
use domain\posts\PostsRepository;
use domain\users\UserException;

class SingleController
{
    /**
     * Single post controller
     * @param int $post_id
     * @return array
     */
    public static function single(int $post_id): array
    {
        try {
            // Context parts
            $head_context = ContextHelper::headContext();
            $header_context = ContextHelper::headerContext();
            $single_context = ContextHelper::singleContext();
            $widgets_context = ContextHelper::widgetsContext();
            $footer_context = ContextHelper::footerContext();
            $head_context["title"] = "Single post";

            $mysql_conf = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
            $pdo = $mysql_conf->getPDO();
            $repo = PostsRepository::init($pdo);
            $categories_repo = CategoriesRepository::init($pdo);

            $categories = $categories_repo->findAll();
            $post = $repo->findById($post_id);

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

            if (isset($post)) {
                $single_context["id"] = $post->getId();
                $single_context["title"] = $post->getTitle();
                // TODO: content parser
                $single_context["content"] = $post->getContent();
                $single_context["user"] = [
                    "link" => Router::link(
                        "/users/" . $post->getUser()->getId(),
                        $_ENV["URL_PREFIX"]
                    ),
                    "full_name" => $post->getUser()->getFullName()
                ];
                $avatar = $post->getUser()->getAvatar();
                if (isset($avatar)) {
                    $single_context["user"]["avatar"]["src"] = Router::link(
                        "/static/users/" . $post->getUser()->getAvatar(),
                        $_ENV["URL_PREFIX"]
                    );
                } else {
                    $single_context["user"]["avatar"]["src"] = Router::link(
                        "/assets/images/default_avatar.png",
                        $_ENV["URL_PREFIX"]
                    );
                }
                $single_context["date"] = $post->getCreationDateTime();
                $single_context["rating"] = $post->getRating();
                $single_context["count_saved"] = $post->getCountSaved();
                $single_context["asset_links"] = [
                    "save" => Router::link("/assets/images/save.svg", $_ENV["URL_PREFIX"]),
                    "like" => Router::link("/assets/images/like.svg", $_ENV["URL_PREFIX"]),
                ];
            }

            return [
                "template" => "single",
                "context" => [
                    "head" => $head_context,
                    "header" => $header_context,
                    "single" => $single_context,
                    "widgets" => $widgets_context,
                    "footer" => $footer_context
                ]
            ];
        } catch (UserException $e) {
            throw $e;
        }
    }
}