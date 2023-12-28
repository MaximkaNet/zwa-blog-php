<?php
use app\core\router\Router;
use app\core\Application;
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once "components/common/head.php"; ?>
<body class="main-body auth-padding">
<ul class="auth-errors"></ul>
<?php
// Select view to render
if(isset($_SESSION["user"]) and $_SESSION["user"]["is_auth"])
    require_once "components/auth/status.php";
else
    require_once "components/auth/form-login.php";
?>
<script src="<?= Router::link(
        "/assets/js/auth.js",
        Application::getRouter()->getPrefix()); ?>" type="module"></script>
</body>
</html>
