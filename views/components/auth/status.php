<?php
use app\core\Application;
use app\core\router\Router;

$router = Application::getRouter();
?>

<form class="auth-form center secondary-bg" id="logout_form" method="post">
    <img class="logo" src="<?= Router::link("/assets/images/logo.svg", $router->getPrefix()); ?>" alt="logo">
    <p class="auth-info">Already logged in as: <b><?= $_SESSION["user"]["email"]; ?></b></p>
    <div class="auth-form-section">
        <a
            class="primary-btn"
            href="<?= Router::link("/", $router->getPrefix()); ?>"
        >Continue</a>
        <button
            id="auth_logout"
            class="secondary-btn"
        >Log out</button>
    </div>
</form>