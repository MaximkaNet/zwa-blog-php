<?php

use app\controllers\api\PostsAPIController;

$router->post("/api/v1/posts/create", [PostsAPIController::class, 'create']);
$router->post("/api/v1/posts/:id/edit", [PostsAPIController::class, 'edit']);
$router->post("/api/v1/posts/:id/like", [PostsAPIController::class, 'like']);
$router->post("/api/v1/posts/:id/save", [PostsAPIController::class, 'save']);
