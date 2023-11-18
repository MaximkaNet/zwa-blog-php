<?php
use app\core\Application;
use app\core\router\Router;
Application::setPageName('Login');
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once "../views/common/head.php"; ?>
<body class="bg-solid full-width full-height">
    <?php
//        if($_SERVER["REQUEST_METHOD"] == "POST") {
//            if($_POST['email'] == 'mail@gmail.com' && $_POST['password'] == '123'){
//                session_start();
//                $_SESSION['is_auth'] = true;
//                $_SESSION['id'] = 123;
//                header('Location: ' . Router::absoluteLink('/'));
//            }
//            else{
//                echo 'Incorrect data!';
//            }
//        }
    ?>
    <form class="login-form mg-center flex-container flex-col max-w-200 relative" method="post">
        <img class="logo mg-center" src="../../assets/images/logo.svg" alt="logo">
        <div class="login-input-wrapper flex-container flex-items-center">
            <label for="login-form-email" class="flex-container flex-items-center">
                <img class="input-icon" src="../../assets/images/email.svg" alt="email">
            </label>
            <input
                id="login-form-email"
                type="email"
                name="email"
                placeholder="email"
                value="<?php //echo $_POST['email'] == "" ? "" : $_POST['email'] ?>"
            >
        </div>
        <div class="login-input-wrapper input-container flex-container">
            <label for="login-form-password" class="flex-container flex-items-center">
                <img class="input-icon" src="../../assets/images/password.svg" alt="password">
            </label>
            <input
                id="login-form-password"
                type="password"
                name="password"
                placeholder="password"
            >
        </div>
        <button class="btn-login-form btn-signup">Login</button>
        <span><a href="../signup" class="link-orange">Sign up</a>if you don't have an account yet</span>
    </form>
</body>
</html>
