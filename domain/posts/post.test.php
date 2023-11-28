<?php

namespace test\model;

include_once "../../core/test.php";

use app\domain\entity\Category;
use test\Test;

// Entity
require_once "post.php";
use app\domain\entity\Post;

$test_handler = new Test();
$test_handler->addTest("Get keys", function (){
    return var_export(Post::getPropertyKeys(Post::class), true);
});
$test_handler->addTest("Get keys. Exclude: id, is_edited", function (){
    return var_export(Post::getPropertyKeys(Post::class, ["id", "is_edited"]), true);
});
$test_handler->addTest("To associative array", function (){
    $post = new Post();
    $post->editTitle("New title");
    return var_export($post->toAssoc(), true);
});
$test_handler->addTest("To associative array exclude id", function (){
    $post = new Post();
    $post->editTitle("New title");
    $post->changeCategory(new Category(1,"cpp" ));
    return var_export($post->toAssoc(["id", "is_edited", "category"]), true);
});
$test_handler->start();