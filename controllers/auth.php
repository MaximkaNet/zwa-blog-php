<?php

namespace app\controllers;

use app\core\Application;
use app\core\router\Router;

class AuthController
{
    /**
     * Render view
     * @return void
     */
    public static function login(): void
    {
        Application::setPageName('Login');
        require_once "../views/login.php";
    }

    /**
     * Render view
     * @return void
     */
    public static function signup(): void
    {
        Application::setPageName('Signup');
        require_once "../views/signup.php";
    }

    /**
     * Render view
     * @return void
     */
    public static function logout(): void
    {
        if(empty($_SESSION["user"])) {
            $to_login = Router::absoluteLink("/login", Application::getRouter()->getPrefix());
            header("Location: $to_login", true, 301);
        }
        else {
            Application::setPageName('Logout');
            require_once "../views/logout.php";
        }
    }
}