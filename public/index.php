<?php
require_once "../core/router.php";
use app\core\router\Router;
require_once "../core/app.php";
use app\core\Application;
require_once "../core/databaseConfig.php";
use app\core\DatabaseConfiguration;

require_once "config.php";

$ROUTER = new Router(PREFIX);

/* Configuration */

Application::setWebsiteName(WEBSITE_NAME);
Application::setHomeDir(HOME_DIR);

Application::setRouter($ROUTER);

$config = new DatabaseConfiguration(DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD);
$tables = [CATEGORIES_TABLE, USERS_TABLE, POSTS_TABLE, COMMENTS_TABLE];
Application::initDatabase($config, $tables);

require_once "app.php";