<?php

use app\controllers\PublicController;

//$router->get('/', function () {
//    $link = Router::link("/category/articles", Application::getRouter()->getPrefix());
//    header("Location: " . $link, true, 301);
//});
$router->get('/category/articles/:page?', [PublicController::class, 'home']);
//$router->get('/category/:name', function ($name) {
//    header("Location: " . Router::link("/category/" . $name . "/articles", Application::getRouter()->getPrefix()));
//});
$router->get('/category/:name/articles/:page?', [PublicController::class, 'articlesByCategory']);
$router->get('/articles/:id', [PublicController::class, 'single']);
$router->get('/users/:id', [PublicController::class, 'userProfile']);
$router->get('/search', [PublicController::class, 'search']);

