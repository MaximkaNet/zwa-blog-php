<?php

use app\core\Application;
use app\controllers\AuthController;
use app\controllers\AuthAPIController;

require_once $_SERVER["DOCUMENT_ROOT"] . "/controllers/api/auth.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/controllers/auth.php";

$router = Application::getRouter();

// Render views
$router->get('/login', [AuthController::class, 'login']);
$router->get('/signup', [AuthController::class, 'signup']);
$router->get('/logout', [AuthController::class, 'logout']);

// Auth api
$router->post('/api/v1/login', [AuthAPIController::class, 'login']);
$router->post('/api/v1/signup',  [AuthAPIController::class, 'signup']);
$router->post('/api/v1/logout', [AuthAPIController::class, 'logout']);