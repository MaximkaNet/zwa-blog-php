<?php

use app\core\Application;
use app\core\router\Router;

$router = Application::getRouter();
?>

<form class="auth-form center secondary-bg" method="post" id="logout_form">
    <img class="logo" src="<?= Router::link("/assets/images/logo.svg", $router->getPrefix()); ?>" alt="logo">
    <p class="auth-info">Do you really want to log out?</p>
    <div class="auth-form-section">
        <button
            id="auth_logout"
            class="secondary-btn"
        >Yes</button>
        <a
            type="submit"
            class="primary-btn"
            href="<?= Router::link("/", $router->getPrefix()); ?>"
        >No</a>
    </div>
</form>