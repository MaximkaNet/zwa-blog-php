<?php

require_once 'controllers\auth.php';

use app\core\Application;
use app\controllers\AuthController;

$router = Application::getRouter();

$router->setRoute('/login', 'get|post', [AuthController::class, 'login']);
$router->setRoute('/signup', 'get|post', [AuthController::class, 'signup']);

$router->get('/logout', [AuthController::class, 'logout']);