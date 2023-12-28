<?php
use app\core\Application;
use app\core\router\Router;
$settings = Application::getWebsiteSettings();
$router = Application::getRouter();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= Router::link("/assets/images/favicon.ico", $router->getPrefix()) ?>">
    <title><?= $settings->getTitle(); ?></title>
    <link rel="stylesheet" href="<?= Router::link("/assets/css/style.css", $router->getPrefix())?>">
    <noscript>Available JavaScript to use this website</noscript>
</head>