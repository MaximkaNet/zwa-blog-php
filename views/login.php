<?php

use app\core\Application;
use app\core\router\Router;

?>

<!DOCTYPE html>
<html lang="en">
<?php //require_once "../common/head.php"; ?>
<body class="main-body login-body">
<?php if(isset($errors["messages"])): ?>
    <ul class="auth-errors">
        <?php foreach ($errors["messages"] as $message): ?>
        <?php include "error.php"; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php
    if(isset($already_logged_in)):
        require_once "status.php";
    else:
    ?>
    <form class="login-form" method="post">
        <img class="logo" src="<?php echo Application::linkFor("/assets/images/logo.svg"); ?>" alt="logo">
        <fieldset>
            <section>
                <label for="login-form-email">
                    <img src="<?php echo Application::linkFor("/assets/images/email.svg"); ?>" alt="email">
                    <?php $email_login = ""; ?>
                    <?php
                    if (isset($errors["data"])) {
                        $email_login = $errors["data"]["email"];
                    }
                    ?>
                    <input
                        id="login-form-email"
                        type="email"
                        name="email"
                        placeholder="email"
                        value="<?= $email_login ?>"
                    >
                </label>
            </section>
            <section>
                <label for="login-form-password">
                    <img src="<?php echo Application::linkFor("/assets/images/password.svg"); ?>" alt="password">
                    <input
                            id="login-form-password"
                            type="password"
                            name="password"
                            placeholder="password"
                    >
                </label>
            </section>
            <button class="primary-btn">Login</button>
        </fieldset>
        <span><a href="<?php echo Router::absoluteLink("/signup", Application::getRouter()->getPrefix())?>" class="link-orange">Sign up</a>&nbsp;if you don't have an account yet</span>
    </form>
<?php endif; ?>
<?php require_once "../views/common/footer.php"?>
</body>
</html>
