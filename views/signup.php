<?php
use app\core\Application;
use app\core\router\Router;
?>
<!doctype html>
<html lang="en">
<?php include_once "../views/common/head.php"; ?>
<body class="signup-body">
    <?php if(isset($errors)): ?>
    <div>Errors: <?php var_dump($errors); ?></div>
    <?php endif; ?>
    <form class="signup-form" method="post">
        <img class="logo" src="<?php echo Application::linkFor("/assets/images/logo.svg"); ?>" alt="logo">
        <fieldset>
            <div class="signup-fieldset">
                <label for="signup-form-first_name">First name*</label>
                <input
                    id="signup-form-first_name"
                    type="text"
                    name="first_name"
                    placeholder="first name"
                    required
                >
            </div>
            <div class="signup-fieldset">
                <label for="signup-form-last_name">Last name</label>
                <input
                    id="signup-form-last_name"
                    type="text"
                    name="last_name"
                    placeholder="last name"
                >
            </div>
            <div class="signup-fieldset">
                <label for="signup-form-email">Email*</label>
                <input
                    id="signup-form-email"
                    type="email"
                    name="email"
                    placeholder="email"
                    required
                >
            </div>
            <div class="signup-fieldset">
                <label for="signup-form-password">Password*</label>
                <input
                    id="signup-form-password"
                    type="password"
                    name="password"
                    placeholder="password"
                    required
                >
            </div>
            <div class="signup-fieldset">
                <label for="signup-form-confirm_password">Confirm password*</label>
                <input
                    id="signup-form-confirm_password"
                    type="password"
                    name="confirm_password"
                    placeholder="confirm password"
                    required
                >
            </div>
            <button class="primary-btn">Confirm</button>
        </fieldset>
        <span><a href="<?php echo Router::absoluteLink("/login", Application::getRouter()->getPrefix())?>" class="link-orange">Login</a>&nbsp;if you have account</span>
    </form>
</body>
</html>