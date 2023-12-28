<?php
use app\core\Application;
use app\core\router\Router;

$router = Application::getRouter();
?>

<form class="auth-form center" method="post" id="signup_form">
    <img class="logo" src="<?= Router::link("/assets/images/logo.svg", $router->getPrefix()); ?>" alt="logo">
    <fieldset>
        <div class="auth-form-section column-group">
            <span class="input-title">First name*</span>
            <label for="first_name" class="auth-form-input">
                <input
                    id="first_name"
                    type="text"
                    name="first_name"
                    placeholder="first name"
                    required
                >
            </label>
        </div>
        <div class="auth-form-section column-group">
            <span class="input-title">Last name</span>
            <label for="last_name" class="auth-form-input">
                <input
                    id="last_name"
                    type="text"
                    name="last_name"
                    placeholder="last name"
                >
            </label>
        </div>
        <div class="auth-form-section column-group">
            <span class="input-title">Email*</span>
            <label for="email" class="auth-form-input">
                <input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="email"
                    required
                >
            </label>
        </div>
        <div class="auth-form-section column-group">
            <span class="input-title">Password*</span>
            <label for="password" class="auth-form-input">
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="password"
                    required
                >
            </label>
        </div>
        <div class="auth-form-section column-group">
            <span class="input-title">Confirm password*</span>
            <label for="confirm_password" class="auth-form-input">
                <input
                    id="confirm_password"
                    type="password"
                    name="confirm_password"
                    placeholder="confirm password"
                    required
                >
            </label>
        </div>
    </fieldset>
    <div class="auth-form-section">
        <button class="primary-btn">Confirm</button>
        <span class="extra-action">
            <a
                href="<?= Router::link("/login", $router->getPrefix())?>"
                class="link-orange"
            >Login</a>&nbsp;if you have account</span>
    </div>
</form>