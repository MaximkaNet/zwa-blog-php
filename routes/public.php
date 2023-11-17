<?php

require_once "controllers\public.php";

use app\core\Application;
use app\controllers\PublicController;

$router = Application::getRouter();

$router->get('/', [PublicController::class, 'home']);
$router->get('/category/:name', [PublicController::class, 'category']);
$router->get('/articles/:id', [PublicController::class, 'single']);
$router->get('/users/:id', [PublicController::class, 'user']);
$router->get('/search', [PublicController::class, 'search']);

