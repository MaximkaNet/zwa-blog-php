<?php


// Admin routes
$router->get('/admin', [AdminController::class, 'dashboard']);
//$router->get('/admin/create-article', [AdminController::class, 'createArticle']);
$router->get('/admin/edit-article/:article_id', [AdminController::class, 'editArticle']);
$router->get('/admin/settings', [AdminController::class, 'settings']);