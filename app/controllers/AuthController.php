<?php

namespace app\controllers;

use app\app\core\Application;
use app\app\core\Router;

class AuthController
{
    /**
     * Render view
     * @return void
     */
    public static function login(): void
    {
        Application::getWebsiteSettings()->setPage('Login');
        require_once "../views/login.mustache";
    }

    /**
     * Render view
     * @return void
     */
    public static function signup(): void
    {
        Application::getWebsiteSettings()->setPage('Signup');
        require_once "../views/signup.mustache";
    }

    /**
     * Render view
     * @return void
     */
    public static function logout(): void
    {
        if(empty($_SESSION["user"])) {
            $to_login = Router::link("/login", Application::getRouter()->getPrefix());
            header("Location: $to_login", true, 301);
        }
        else {
            Application::getWebsiteSettings()->setPage('Logout');
            require_once "../views/logout.mustache";
        }
    }
}