<?php
// Admin routes
use app\controllers\AdminController;

$router->get('/admin', [AdminController::class, 'myPosts']);
$router->get('/admin/profile', [AdminController::class, 'profile']);
$router->get('/admin/edit-article/:article_id', [AdminController::class, 'editArticle']);
$router->get('/admin/settings', [AdminController::class, 'settings']);