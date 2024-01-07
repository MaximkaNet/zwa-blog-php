<?php

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
// Change users avatar
$router->post('/api/users/:user_id/avatar', [UserController::class, 'changeAvatar']);
// Change users info
$router->post('/api/users/:user_id/edit', [UserController::class, 'changeInfo']);
// Delete users
$router->get('/api/users/:user_id/delete', [UserController::class, 'delete']);