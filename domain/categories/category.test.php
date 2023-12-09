<?php

namespace app\domain\categories;

require_once "../../core/utils/test.php";
use app\core\utils\Test;

// Category entity
require_once "category.php";

$test_handler = new Test();
$test_handler->addTest("Get keys", function (){
    return var_export(Category::getPropertyKeys(Category::class, ["id", "password"]), true);
});
$test_handler->addTest("To associative array", function (){
    $category = new Category();
    return var_export($category->toAssoc(), true);
});
$test_handler->start();