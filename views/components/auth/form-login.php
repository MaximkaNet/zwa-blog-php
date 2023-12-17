<?php
use app\core\Application;
use app\core\router\Router;
?>

<form class="auth-form secondary-bg center" id="login_form" method="post">
    <img class="logo" src="<?php echo Application::linkFor("/assets/images/logo.svg"); ?>" alt="logo">
    <fieldset>
        <label for="login-form-email" class="auth-form-input">
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
        <label for="login-form-password" class="auth-form-input">
            <img src="<?php echo Application::linkFor("/assets/images/password.svg"); ?>" alt="password">
            <input
                    id="login-form-password"
                    type="password"
                    name="password"
                    placeholder="password"
            >
        </label>
    </fieldset>
    <div class="auth-form-section">
        <button class="primary-btn" type="submit">Login</button>
        <span class="extra-action"><a href="<?php echo Router::absoluteLink("/signup", Application::getRouter()->getPrefix())?>" class="link-orange">Sign up</a>&nbsp;if you don't have an account yet</span>
    </div>
</form>