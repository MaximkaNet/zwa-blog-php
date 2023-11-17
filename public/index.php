<?php

require_once "config.php";
require_once "../core/app.php";
require_once "../core/router.php";
require_once "../core/database/TableBuilder.php";
require_once "../core/database/ColumnBuilder.php";
require_once "../core/database/Datatype.php";
require_once "../core/database/MysqlProvider.php";

use app\core\database\ColumnBuilder;
use app\core\database\Datatype;
use app\core\database\TableBuilder;

use app\core\router\Router;
use app\core\Application;

use app\core\orm\MysqlProvider as MysqlDatabase;

Application::setWebsiteName(WEBSITE_NAME);

$router = new Router(/*'/~' . constant('USERNAME')*/);
Application::setRouter($router);

$database = new MysqlDatabase(
    DB_HOST,
    DB_USERNAME,
    DB_PASSWORD,
    DB
);
Application::setDatabase($database);

$database->define('user', [
    "id" => [
        "type" => Datatype::INTEGER(),
        "primary_key" => true,
        "allow_null" => false,
        "auto_increment" => true
    ],
    "email" => [
        "type" => Datatype::STRING(255),
        "unique" => true,
        "allow_null" => false
    ],
    "password" => [
        // Bcrypt hash
        "type" => Datatype::STRING(60),
        "allow_null" => false
    ],
    "first_name" => [
        "type" => Datatype::STRING(255),
        "allow_null" => false
    ],
    "last_name" => Datatype::STRING(255),
    "avatar" => Datatype::STRING(255)
]);

$database->model('');
// Define tables
$users_table = new TableBuilder('users');
$users_table->define([
    ColumnBuilder::build('id', Datatype::INTEGER())->primaryKey()->autoIncrement()->notNull(),
    ColumnBuilder::build('email', Datatype::STRING(255))->unique()->notNull(),
    ColumnBuilder::build('password', Datatype::STRING(255))->notNull(),
    ColumnBuilder::build('first_name', Datatype::STRING(255))->notNull(),
    ColumnBuilder::build('last_name', Datatype::STRING(255)),
    ColumnBuilder::build('avatar', Datatype::STRING(255))
]);

$posts_table = new TableBuilder('posts');
$posts_table->define([
    ColumnBuilder::build('id', Datatype::INTEGER())->primaryKey()->autoIncrement()->notNull(),
    ColumnBuilder::build('title', Datatype::STRING(255))->notNull(),
    ColumnBuilder::build('content', Datatype::MEDIUMTEXT())->notNull(),
    ColumnBuilder::build('rating', Datatype::INTEGER())->default(0),
    ColumnBuilder::build('count_saved', Datatype::INTEGER())->default(0),
]);

// Page routes
include "routes/public.php";

// Auth
include "routes/auth.php";

// Admin
include "routes/admin.php";

// Api routes
//include "routes/api.php";

// Not found page
$router->notFound(function (){
    include "views/404.php";
});

// Match the request path
//$router->resolve(Router::getPath(), Router::getMethod());

