<?php
use app\core\Application;
use app\core\router\Router;
?>

<!doctype html>
<html lang="en">
<?php require_once "components/common/head.php"; ?>
<body class="signup-body auth-padding">
<ul class="auth-errors"></ul>
<?php require_once "components/auth/form-signup.php"; ?>
<script src="<?= Router::link(
        "assets/js/auth.js",
        Application::getRouter()->getPrefix()); ?>" type="module"></script>
</body>
</html>