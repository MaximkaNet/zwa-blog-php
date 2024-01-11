<?php
// Admin routes
use app\controllers\AdminController;

$router->get('/admin', [AdminController::class, 'myPosts']);
$router->get('/admin/profile', [AdminController::class, 'profile']);
$router->get('/admin/article/:article_id/edit', [AdminController::class, 'editArticle']);
$router->get('/admin/article/:article_id', [AdminController::class, 'previewArticle']);
$router->get('/admin/article', [AdminController::class, 'createArticle']);
$router->get('/admin/settings', [AdminController::class, 'settings']);