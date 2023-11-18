<?php

require_once "config.php";
require_once "../core/app.php";
require_once "../core/router.php";

use app\core\router\Router;
use app\core\Application;

Application::setWebsiteName(WEBSITE_NAME);
Application::setHomeDir(HOME_DIR);

$router = new Router(HOME_DIR);
Application::setRouter($router);

// Page routes
include "../routes/public.php";

// Auth
include "../routes/auth.php";

// Admin
include "../routes/admin.php";

// Api routes
//include "routes/api.php";

// Not found page
$router->notFound(function (){
    include "../views/404.php";
});

// Match the request path
$router->resolve(Router::getPath(), Router::getMethod());

