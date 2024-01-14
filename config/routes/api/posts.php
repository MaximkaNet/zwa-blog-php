<?php

use app\controllers\api\PostsAPIController;

$router->post("/api/v1/posts/create", [PostsAPIController::class, 'create']);
$router->post("/api/v1/posts/:id/edit", [PostsAPIController::class, 'edit']);
$router->delete("/api/v1/posts/:id/delete", [PostsAPIController::class, 'delete']);
