<?php

use app\controllers\api\CommentsAPIController;

$router->post('/api/v1/comments/create', [CommentsAPIController::class, 'create']);
$router->post('/api/v1/comments/reply_to/:comment_id', [CommentsAPIController::class, 'replyTo']);
$router->post('/api/v1/comments/:id/edit', [CommentsAPIController::class, 'edit']);
$router->delete('/api/v1/comments/:id/delete', [CommentsAPIController::class, 'delete']);
