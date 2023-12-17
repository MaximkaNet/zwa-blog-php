<?php

use app\core\Application;
use app\core\router\Router;

?>

<form class="auth-form center secondary-bg" method="post" id="logout_form">
    <img class="logo" src="<?php echo Application::linkFor("/assets/images/logo.svg"); ?>" alt="logo">
    <p class="auth-info">Do you really want to log out?</p>
    <div class="auth-form-section">
        <button
            id="auth_logout"
            class="secondary-btn"
        >Yes</button>
        <a
            type="submit"
            class="primary-btn"
            href="<?php echo Router::absoluteLink("/", Application::getRouter()->getPrefix()); ?>"
        >No</a>
    </div>
</form>