<?php

use app\controllers\api\UsersAPIController;

$router->post("/api/v1/users/:id/edit", [UsersAPIController::class, 'edit']);
$router->post("/api/v1/users/:id/delete", [UsersAPIController::class, 'delete']);
