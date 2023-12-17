<?php
use app\core\Application;
use app\core\router\Router;
?>

<form class="auth-form center secondary-bg" id="logout_form" method="POST">
    <img class="logo" src="<?php echo Application::linkFor("/assets/images/logo.svg"); ?>" alt="logo">
    <p class="auth-info">Already logged in as: <b><?= $_SESSION["user"]["email"]; ?></b></p>
    <div class="auth-form-section">
        <a
            class="primary-btn"
            href="<?= Router::absoluteLink("/", Application::getRouter()->getPrefix()); ?>"
        >Continue</a>
        <button
            id="auth_logout"
            class="secondary-btn"
        >Log out</button>
    </div>
</form>