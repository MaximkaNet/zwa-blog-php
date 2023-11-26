<?php

use app\core\Application;
use app\core\router\Router;

$topics = [
        '/'=>'All posts',
    '/category/web-development'=> 'Web development',
    '/category/design' => 'Design',
    '/category/python' => 'Python',
    '/category/cplusplus' => 'C++'
];
$current_path = '/';

?>
<nav class="navbar">
    <div class="flex-container">
        <a href="/">
            <img class="logo" src="<?php echo Application::linkFor("/assets/images/logo.svg")?>" alt="Logo image">
        </a>
        <div class="topics flex-container flex-items-center">
            <?php foreach ($topics as $url => $title): ?>
            <a
                href="<?php echo Router::absoluteLink($url, Application::getRouter()->getPrefix()) ?>"
                class="topic-link link-white <?php echo $current_path == $url ? "current" : "" ?>"
            >
                <?php echo $title ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="flex-container">
        <a href="<?php echo Router::absoluteLink("/signup", Application::getRouter()->getPrefix()); ?>" class="primary-btn">Sign up</a>
        <a href="<?php echo Router::absoluteLink("/login", Application::getRouter()->getPrefix());  ?>" class="secondary-btn">Login</a>
    </div>
</nav>