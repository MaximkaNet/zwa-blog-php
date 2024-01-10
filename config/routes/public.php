<?php

use app\controllers\ArticlesController;
use app\controllers\ProfileController;
use app\controllers\SearchController;
use app\controllers\SingleController;

$router->get('/', [ArticlesController::class, 'redirectToAll']);
$router->get('/category', [ArticlesController::class, 'redirectToAll']);
$router->get('/category/articles/:page?', [ArticlesController::class, 'all']);
$router->get('/category/:name', [ArticlesController::class, 'redirectToCategory']);
$router->get('/category/:name/articles/:page?', [ArticlesController::class, 'category']);
$router->get('/articles/:id', [SingleController::class, 'single']);
$router->get('/users/:id', [ProfileController::class, 'withArticles']);
$router->get('/users/:id/:page?', [ProfileController::class, 'withArticles']);
$router->get('/search', [SearchController::class, 'search']);

