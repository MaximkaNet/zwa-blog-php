<?php

namespace test\model;

require_once "../../core/test.php";
use test\Test;

// Entity
require_once "user.php";
use app\domain\entity\User;

$test_handler = new Test();
$test_handler->addTest("Get keys", function (){
    return var_export( User::getPropertyKeys(User::class, ["id", "password"]), true);
});
$test_handler->addTest("To associative array", function (){
    $user = new User();
    $user->setId(123);
    $user->changePassword(123);
    return var_export($user->toAssoc(), true);
});
$test_handler->addTest("Get full name", function (){
    $user = new User();
    $user->setFirstName("User 1");
    return $user->getFullName();
});
$test_handler->start();