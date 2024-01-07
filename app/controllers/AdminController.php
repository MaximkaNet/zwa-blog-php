<?php

namespace app\controllers;
use app\app\core\Application;
use app\app\core\Router;

class AdminController {
    public static function dashboard(): void
    {
        if(!isset($_SESSION["users"])) {
            header("Location: " . Router::absoluteLink("/login", Application::getRouter()->getPrefix()));
            return;
        }
        include_once "../views/admin/admin.php";
    }
    public static function createArticle(): void
    {
        if(!isset($_SESSION["users"])) {
            header("Location: " . Router::absoluteLink("/login", Application::getRouter()->getPrefix()));
            return;
        }
        echo "Create article";
    }
    public static function editArticle(): void
    {
        if(!isset($_SESSION["users"])) {
            header("Location: " . Router::absoluteLink("/login", Application::getRouter()->getPrefix()));
            return;
        }
        echo "Edit article";
    }
    public static function settings(): void
    {
        if(!isset($_SESSION["users"])) {
            header("Location: " . Router::absoluteLink("/login", Application::getRouter()->getPrefix()));
            return;
        }
        echo "Settings";
    }
}