<?php
use app\core\Application;
use app\core\DatabaseConfiguration;
use app\core\router\Router;
use app\core\utils\WebsiteSettings;
use app\core\components\Menu;
use app\core\components\MenuItem;

require_once $_SERVER["DOCUMENT_ROOT"] . "/core/app.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/core/databaseConfig.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/core/utils/websiteSettings.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/core/router.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/core/ui/menu.php";

require_once "config.php";

$router = new Router(PREFIX);

$settings = new WebsiteSettings();
$settings->setName(WEBSITE_NAME);

$db_config = new DatabaseConfiguration(DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD);
$tables = [CATEGORIES_TABLE, USERS_TABLE, POSTS_TABLE, COMMENTS_TABLE];

$menu = new Menu();
$menu->addItem(new MenuItem("/", "All", true));
$menu->addItem(new MenuItem("cpp", "C++"));
$menu->addItem(new MenuItem("python", "Python"));
$menu->addItem(new MenuItem("design", "Design"));
$menu->addItem(new MenuItem("web-development", "Web development"));

Application::setWebsiteSettings($settings);
Application::setRouter($router);
Application::initDatabase($db_config, $tables);
Application::addMenu("main", $menu);

require_once "app.php";