<?php

require "../../../../vendor/autoloader.php";

use app\core\utils\queryBuilder\QueryBuilder;
use app\core\utils\Test;

$test_handler = new Test();
$test_handler->addTest("Crash test query builder", function () {
    $qb = new QueryBuilder();
    // Configure query
    $qb->select([
        "posts" => [
            ["id" => "post_id"],
            ["title" => "post_title"]
        ],
        "users" => [
            ["id" => "user_id"]
        ],
        "categories" => [
            ["id" => "category_id"]
        ]
    ])->from("posts")
        ->innerJoin("posts", "category_id", "categories", "id")
        ->innerJoin("posts", "user_id", "users", "id")
        ->setFirstResults(0)
        ->setMaxResults(3);
    // Prepare query
    $pdo = new PDO("mysql:host=localhost;dbname=zwa-blog", "root", "");
    $stmt = $pdo->prepare($qb->getSQL());
    $values_to_bind = $qb->getParamsWithValuesWithTypes();
    if (isset($values_to_bind)) {
        foreach ($values_to_bind as $param => ["type" => $type, "value" => $value]) {
            $stmt->bindParam($param, $value, $type);
        }
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo var_export($result, true);
});

$test_handler->addTest("Crash test query builder with ColumnsProvider", function () {
    $qb = new QueryBuilder();

    // Configure columns using columns provider
    $cp = QueryBuilder::createColumnsProvider();
    $cp->addTable("posts")
        ->setEntityName("post")
        ->setColumns(["id", "title", "content"]);
    $cp->addTable("users")
        ->setEntityName("user")
        ->setColumns(["id", "email"]);
    $cp->addTable("categories")
        ->setEntityName("category")
        ->setColumns(["id", "name", "display_name"]);
    // Configure query
    $qb->select($cp->generateColumns())->from("posts")
        ->innerJoin("posts", "category_id", "categories", "id")
        ->innerJoin("posts", "user_id", "users", "id")
        ->setFirstResults(0)
        ->setMaxResults(3);
    // Prepare query
    $pdo = new PDO("mysql:host=localhost;dbname=zwa-blog", "root", "");
    $stmt = $pdo->prepare($qb->getSQL());
    $values_to_bind = $qb->getParamsWithValuesWithTypes();
    if (isset($values_to_bind)) {
        foreach ($values_to_bind as $param => ["type" => $type, "value" => $value]) {
            $stmt->bindParam($param, $value, $type);
        }
    }
    $stmt->execute();
    $result = QueryBuilder::parseQueryResult($stmt->fetchAll(PDO::FETCH_ASSOC), true);
    echo var_export($result, true);
});
// Start test bench
$test_handler->start();