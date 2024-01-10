<?php
require_once "../vendor/autoloader.php";
require_once "../vendor/autoload.php";
require_once "../vendor/mustache/src/Mustache/Autoloader.php";

Mustache_Autoloader::register();

use app\core\Application;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['URL_PREFIX', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD']);

$app = new Application();

$router = $app->createRouter($_ENV["URL_PREFIX"]);

// Configure routes
require "../config/routes/index.php";

$app->applyRouter($router);

$app->run();