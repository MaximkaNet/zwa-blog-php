<?php use app\core\Application; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo Application::linkFor("/assets/images/favicon.ico"); ?>">
    <title><?php echo Application::getPageTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo Application::linkFor("/assets/css/style.css")?>">
    <noscript>Available JavaScript to use this website</noscript>
</head>