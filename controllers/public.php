<?php

namespace app\controllers;

use app\core\Application;

class PublicController
{
    public static function home(): void
    {
        Application::setPageName('Home');

        $posts = PostsService::getAll();

        include "views\home.php";
    }

    public static function search(): void
    {
        Application::setPageName('Search');
    }

    public static function single(int $post_id): void
    {
        Application::setPageName('Single post');
    }

    public static function category($title): void
    {
        echo 'Category title ' . $title;
    }

    public static function user(int $id): void
    {
        Application::setPageName("User: $id");
    }
}