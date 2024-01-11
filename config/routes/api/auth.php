<?php

use app\controllers\api\AuthAPIController;

$router->post('/api/v1/login', [AuthAPIController::class, 'login']);
$router->post('/api/v1/signup',  [AuthAPIController::class, 'signup']);
$router->post('/api/v1/logout', [AuthAPIController::class, 'logout']);