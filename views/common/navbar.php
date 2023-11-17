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
<nav class="navbar flex-container flex-content-between">
    <div class="flex-container">
        <a href="/">
            <img class="logo" src="../../assets/images/logo.svg" alt="Logo image">
        </a>
        <ul class="topics flex-container flex-items-center">
            <?php foreach ($topics as $url => $title): ?>
            <li class="topic">
                <a
                    href="<?php echo Router::absoluteLink($url, Application::getRouter()->getPrefix()) ?>"
                    class="topic-link link-white <?php echo $current_path == $url ? "current" : "" ?>"
                >
                    <?php echo $title ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="flex-container flex-items-center">
        <div class="search-container flex-container flex-items-center overflow-hidden">
            <input id="search" type="search" name="search" placeholder="Vyhledat">
            <label for="search">
                <img src="../../assets/images/search.svg" alt="search">
            </label>
        </div>
    </div>
</nav>