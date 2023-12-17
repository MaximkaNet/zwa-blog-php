<?php

use app\core\Application;
use app\core\router\Router;

//$categories = [
//        '/'=>'All posts',
//    '/category/web-development'=> 'Web development',
//    '/category/design' => 'Design',
//    '/category/python' => 'Python',
//    '/category/cplusplus' => 'C++'
//];
//$current_path = Router::getPath();

?>
<header class="layout-header">
    <div class="layout-header-container">
        <a
            class="logo-container"
            href="<?= Router::absoluteLink("/", Application::getRouter()->getPrefix())?>"
        >
            <img
                class="logo"
                src="<?= Application::linkFor("/assets/images/logo.svg")?>"
                alt="Logo image"
            >
        </a>
        <nav class="categories">
            <?php
//            $menu_items = Application::getMenu()->getItems();
            $menu_items = [];
            ?>
            <?php foreach ($menu_items as $item): ?>
                <a
                    href="<?= Router::absoluteLink($item->getName(), Application::getRouter()->getPrefix()) ?>"
                    class="category-link link-white <?= $item->isCurrent() ? "current" : "" ?>"
                >
                    <?= $item->getDisplayName(); ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <?php if(isset($_SESSION["user"])): ?>
            <div class="layout-header-account">
                <a href="<?= Router::absoluteLink("/admin", Application::getRouter()->getPrefix()); ?>" class="link-white">Account</a>
            </div>
        <?php else: ?>
            <div class="layout-header-account">
                <a href="<?= Router::absoluteLink("/signup", Application::getRouter()->getPrefix()); ?>" class="primary-btn">Sign up</a>
                <a href="<?= Router::absoluteLink("/login", Application::getRouter()->getPrefix());  ?>" class="secondary-btn">Login</a>
            </div>
        <?php endif; ?>
    </div>
</header>