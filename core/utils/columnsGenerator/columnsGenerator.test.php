<?php

require_once "../test.php";
use app\core\utils\Test;

require_once "columnsGenerator.php";
use app\core\utils\columnsGenerator\ColumnsGenerator;

$test_handler = new Test();
$test_handler->addTest("Create a simple columns", function () {
    $post_cols = ["id", "title", "content", "author", "category"];
    $columns_generator = new ColumnsGenerator("posts", $post_cols);
    $rules = [];
    $columns_generator->setRules($rules);
    return var_export($columns_generator->generateSchemeAssoc(), true);
});
$test_handler->addTest("Create nested columns", function () {
    $post_cols = ["id", "title", "content", "author", "category"];
    $columns_generator = new ColumnsGenerator("posts", $post_cols);
    $user_cols = ["id", "email", "first_name"];
    $category_cols = ["id", "name", "display_name"];
    $rules = [
        "author" => ["users" => $user_cols],
        "category" => ["categories" => $category_cols]
    ];
    $columns_generator->setRules($rules);
    return var_export($columns_generator->generateSchemeAssoc(), true);
});
$test_handler->addTest("Rewrite columns", function () {
    $post_cols = ["id", "title", "content", "author", "category"];
    $columns_generator = new ColumnsGenerator("posts", $post_cols);
    $rules = [
        "author" => "user_id",
        "category" => "category_id"
    ];
    $columns_generator->setRules($rules);
    return var_export($columns_generator->generateSchemeAssoc(), true);
});
$test_handler->addTest("Mixed rules", function () {
    $post_cols = ["id", "title", "content", "author", "category"];
    $columns_generator = new ColumnsGenerator("posts", $post_cols);
    $category_cols = ["id", "name", "display_name"];
    $rules = [
        "author" => "user_id",
        "category" => ["categories" => $category_cols]
    ];
    $columns_generator->setRules($rules);
    return var_export($columns_generator->generateSchemeAssoc(), true);
});
$test_handler->start();