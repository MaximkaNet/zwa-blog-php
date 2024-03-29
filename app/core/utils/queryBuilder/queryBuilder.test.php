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
$test_handler->addTest("Insert query", function () {
    $qb = new QueryBuilder();
    $qb->insertInto("posts", [
        "title" => "Hello",
        "content" => "Hi",
        "status" => "posted"
    ]);
    return $qb->getSQL();
});
$test_handler->addTest("Update query", function () {
    $qb = new QueryBuilder();
    $qb->update("posts", [
        "title" => "Hello hi!"
    ])
        ->where([
            "title" => "tets"
        ]);
    $qb->getSQL();
    return var_export($qb->getParamsWithValuesWithTypes(), true);
//    return $qb->getSQL();
});
$test_handler->addTest("Delete query", function () {
    $qb = new QueryBuilder();
    $qb->deleteFrom("posts")
        ->where([
            "posts" => [
                "id" => 1
            ]
        ]);
    echo $qb->getSQL();
    return var_export($qb->getParamsWithValuesWithTypes(), true);
//    return $qb->getSQL();
});
$test_handler->addTest("Sorting results", function () {
    $qb = new QueryBuilder();
    $qb->select()
        ->from("users")
        ->sortAsc("id");
    $sort_asc = $qb->getSQL();
    $qb->sortDesc("id");
    $sort_desc = $qb->getSQL();
    $qb->orderBy(["test", "test23"]);
    $sort_orderBy = $qb->getSQL();
    return var_export([$sort_asc, $sort_desc, $sort_orderBy], true);
});
// Start test bench
$test_handler->start();