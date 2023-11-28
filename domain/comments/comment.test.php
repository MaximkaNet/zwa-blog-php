<?php

namespace test\model;

include_once "../../core/test.php";

use app\domain\entity\User;
use test\Test;

// Entity
require_once "comment.php";
use app\domain\entity\Comment;

$test_handler = new Test();
$test_handler->addTest("Get keys", function (){
    return var_export(Comment::getPropertyKeys(Comment::class), true);
});
$test_handler->addTest("Get keys. Exclude: id, is_edited", function (){
    return var_export(Comment::getPropertyKeys(Comment::class, ["id"]), true);
});
$test_handler->addTest("To associative array", function (){
    $comment = new Comment();
    return var_export($comment->toAssoc(), true);
});
$test_handler->addTest("To associative array", function (){
    $comment = new Comment();
    $comment->setAuthor(new User(1, "mail@mail.com"));
    $comment->editContent("Lorem ipsum test");
    return var_export($comment->toAssoc(["id", "is_edited"]), true);
});
$test_handler->start();