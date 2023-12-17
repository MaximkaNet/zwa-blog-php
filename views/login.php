<!DOCTYPE html>
<html lang="en">
<?php require_once "components/common/head.php"; ?>
<body class="main-body auth-padding">
<!-- Content will insert by javascript function 'login' --->
<ul class="auth-errors"></ul>
<?php
// Select view to render
if(isset($_SESSION["user"]) and $_SESSION["user"]["is_auth"])
    require_once "components/auth/status.php";
else
    require_once "components/auth/form-login.php";
?>
<?php require_once "components/common/scripts.php"?>
</body>
</html>
