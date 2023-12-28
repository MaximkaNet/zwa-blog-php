<?php
use app\core\Application;
use app\core\router\Router;

$router = Application::getRouter();
?>

<form
    class="auth-form secondary-bg center"
    id="login_form"
    method="post"
>
    <img
        class="logo"
        src="<?= Router::link("/assets/images/logo.svg", $router->getPrefix()); ?>"
        alt="logo"
    >
    <fieldset>
        <label for="login-form-email" class="auth-form-input">
            <img src="<?= Router::link("/assets/images/email.svg", $router->getPrefix()); ?>" alt="email">
            <input
                id="login-form-email"
                type="email"
                name="email"
                placeholder="email"
            >
        </label>
        <label for="login-form-password" class="auth-form-input">
            <img src="<?= Router::link("/assets/images/password.svg", $router->getPrefix()); ?>" alt="password">
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
        <span class="extra-action">
            <a
                href="<?= Router::link("/signup", $router->getPrefix())?>"
                class="link-orange"
            >Sign up</a>&nbsp;if you don't have an account yet</span>
    </div>
</form>