<?php

namespace app\core\components;

require_once "../libs/test/test.php";
require_once "menu.php";

use app\core\utils\Test;

$test_handler = new Test();
$test_handler->addTest("Creating menu", function () {
    $menu = new Menu();
    $menu->addItem(new MenuItem("cpp", "C++"));
    $menu->addItem(new MenuItem("python", "Python"));
    $menu->addItem(new MenuItem("design", "Design"));
    $menu->addItem(new MenuItem("web-development", "Web development"));

    $menu->setCurrent("pythonds");

    $menu_items = $menu->getItems();
    foreach ($menu_items as $menu_item) {
        echo $menu_item->isCurrent() ? "Current\n" : "";
        echo "Url: " . $menu_item->getUrl() . "\n";
        echo "Display name: ". $menu_item->getDisplayName() . "\n";
        echo "----\n";
    }
});
$test_handler->start();