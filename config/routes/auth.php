<?php

use app\controllers\api\AuthAPIController;
use app\controllers\AuthController;

$router->get('/login', [AuthController::class, 'login']);
$router->get('/signup', [AuthController::class, 'signup']);
$router->get('/logout', [AuthController::class, 'logout']);