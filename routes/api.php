<?php

require_once "domain/users/controller.php";
require_once "domain/posts/controller.php";
require_once "domain/comments/controller.php";

use app\core\Application;
use app\controllers\users\UserController;
use app\controllers\posts\PostController;
use app\controllers\comments\CommentController;

$router = Application::getRouter();

// Like set/unset
$router->post('/api/posts/:post_id/like', [PostController::class, 'toggleLike']);
// Save set/unset
$router->post('/api/posts/:post_id/save',  [PostController::class, 'toggleSave']);
// Comment make
$router->post('/api/comment/create', [CommentController::class, 'create']);
// Edit comment
$router->post('/api/comments/:comment_id', [CommentController::class, 'edit']);
// Remove comment
$router->get('/api/comments/:comment_id', [CommentController::class, 'delete']);
// Change user avatar
$router->post('/api/users/:user_id/avatar', [UserController::class, 'changeAvatar']);
// Change user info
$router->post('/api/users/:user_id/edit', [UserController::class, 'changeInfo']);
// Delete user
$router->get('/api/users/:user_id/delete', [UserController::class, 'delete']);