<?php
use app\core\Application;
use app\core\router\Router;

$router = Application::getRouter();
session_start();

// Page routes
require "../routes/public.php";

// Auth
require "../routes/auth.php";

// Admin
require "../routes/admin.php";

// Api routes
//require "routes/api.php";

// Not found page
$router->notFound(function (){
    require "../views/404.php";
});

// Match the request path
$router->resolve(Router::getPath(), Router::getRequestMethod());