<?php

use app\core\Application;
require_once '../controllers/auth.php';
use app\controllers\AuthController;
require_once '../controllers/api/auth.php';
use app\controllers\AuthAPIController;

$router = Application::getRouter();

// Render views
$router->get('/login', [AuthController::class, 'login']);
$router->get('/signup', [AuthController::class, 'signup']);
$router->get('/logout', [AuthController::class, 'logout']);

// Auth api
$router->post('/login', [AuthAPIController::class, 'login']);
$router->post('/signup',  [AuthAPIController::class, 'signup']);
$router->post('/logout', [AuthAPIController::class, 'logout']);