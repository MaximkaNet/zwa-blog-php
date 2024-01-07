<?php
require_once "../vendor/autoloader.php";
require_once "../vendor/mustache/mustache/src/Mustache/Autoloader.php";

Mustache_Autoloader::register();

use app\core\Application;

$app = new Application();

$router = $app->createRouter();

// Configure routes
require "../config/routes/index.php";

$app->applyRouter($router);

$app->run();