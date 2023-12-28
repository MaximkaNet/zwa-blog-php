<?php
use app\core\router\Router;
use app\core\Application;
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once "components/common/head.php"; ?>
<body class="main-body auth-padding">
<ul class="auth-errors"></ul>
<?php require_once "components/auth/form-logout.php"; ?>
<script src="<?= Router::link(
    "assets/js/auth.js",
    Application::getRouter()->getPrefix()); ?>" type="module"></script>
</body>
</html>
