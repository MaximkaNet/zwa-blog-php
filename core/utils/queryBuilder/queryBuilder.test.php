<?php

/**
 * Tests for query builder class
 */

namespace app\core\utils;
require_once "../test.php";
require_once "queryBuilder.php";
require_once "queryBuilderException.php";

use app\core\utils\queryBuilder\QueryBuilder;
use app\core\utils\queryBuilder\QueryBuilderException;

require_once "../columnsGenerator/columnsGenerator.php";
use app\core\utils\columnsGenerator\ColumnsGenerator;

define("TABLE_NAME", "users");
define("COLUMN_NAMES", ['id','title', 'content', 'count_saved', 'createdAt']);

/**
 * Select entity by id
 * @param mixed $id
 * @param array|null $options
 * @return mixed
 * @throws QueryBuilderException
 */
function selectById(mixed $id, array $options = null): string
{
    $query_builder = new QueryBuilder();
    $query_builder->select(COLUMN_NAMES)->from(TABLE_NAME);
    if(isset($options)) $query_builder->where(['id' => $id]);
    if(isset($options["include"])){
        throw new \Exception("Todo: include");
    }
    return $query_builder->build();
}

/**
 * Select one entity
 * @param array|null $options
 * @return mixed
 * @throws QueryBuilderException
 */
function selectOne(array $options = null): string
{
    $query_builder = new QueryBuilder();
    $query_builder->select(COLUMN_NAMES)->from(TABLE_NAME);
    if(isset($options)) $query_builder->where($options["where"]);
    $query_builder->limit(0, 1);

    if(isset($options["include"])){
        // TODO: join
    }
    return $query_builder->build();
}

/**
 * Select all entities
 * @param array|null $options
 * @return string
 * @throws QueryBuilderException
 */
function selectAll(array $options = null): string
{
    $query_builder = new QueryBuilder();
    $query_builder->select(COLUMN_NAMES)->from(TABLE_NAME);
    if(isset($options["include"])) {
        $tables = [];
        $relations = [];
        foreach ($options["include"] as $table => $relation){
            $tables[] = $table;
            $relations = array_merge($relations, $relation);
        }
        $query_builder->join(QueryBuilder::INNER_JOIN, $tables, $relations);
    }
    if(isset($options["where"])) $query_builder->where($options["where"]);

    return $query_builder->build();
}

/**
 * Insert entity
 * @param array $values
 * @return string
 * @throws QueryBuilderException
 */
function insert(array $values): string
{
    $query_builder = new QueryBuilder();
    $query_builder->insertInto(TABLE_NAME, $values);

    return $query_builder->build();
}

/**
 * Update entity
 * @param array $values
 * @param array|null $options
 * @return string
 * @throws QueryBuilderException
 */
function update(array $values, array $options = null): string
{
    $timestamp = $options["update_timestamp"] ?? ["updatedAt" => date("Y-m-d H:i:s")];
    $query_builder = new QueryBuilder();
    $query_builder->update(TABLE_NAME, [...$values, ...$timestamp]);
    if(isset($options))
        $query_builder->where($options["where"]);

    return $query_builder->build();
}

/**
 * Delete entity by id
 * @param mixed $id
 * @return string
 * @throws QueryBuilderException
 */
function deleteById(mixed $id): string
{
    $query_builder = new QueryBuilder();
    $query_builder->deleteFrom(TABLE_NAME);
    $query_builder->where(['id' => $id]);

    return $query_builder->build();
}

/**
 * Build query: Delete
 * @param array|null $options
 * @return string
 * @throws QueryBuilderException
 */
function delete(array $options = null): string
{
    $query_builder = new QueryBuilder();
    $query_builder->deleteFrom(TABLE_NAME);
    if(isset($options))
        $query_builder->where($options["where"]);

    return $query_builder->build();
}


$test_handler = new Test();
$test_handler->addTest("Delete function", function () {
    return delete(["where"=>["id" => 1, QueryBuilder::OP_AND, "title" => "name"]]);
});
$test_handler->addTest("Delete by id function", function () {
    return deleteById("dsf");
});
$test_handler->addTest("Update function", function () {
    return update(["title" => "New title"], ["where" => ["id" => 0]]);
});
$test_handler->addTest("Insert function", function () {
    return insert(["title" => "New title"]);
});
$test_handler->addTest("Select all function", function () {
    return selectAll([
        "include" =>[
            "users" => ["user_id" => "users.id"],
            "categories" => ["posts.category_id" => "categories.id"]
        ]
    ]);
});
$test_handler->addTest("Select one function", function () {
    return selectOne();
});
$test_handler->addTest("Select by id function", function () {
    return selectById(0);
});
$test_handler->addTest("Get values to bind", function (){
    $qb = new QueryBuilder();
    $qb->select()->from("test");
    $qb->where(["id" => 1, "name" => "User"]);
    return var_export($qb->getValuesToBind(), true);
});
$test_handler->addTest("Generate columns using ColumnsGenerator", function (){
    $qb = new QueryBuilder();

    $cg = new ColumnsGenerator();
    $cg->setColumns(["id", "name", "last_name", "birthday", "category"]);
    $cg->setRules(["category" => "category_name"]);
    $generated_cols = $cg->generateSchemeAssoc();

    $qb->select($generated_cols)->from("test");
    $qb->where(["id" => 1, "name" => "User"]);
    return var_export($qb->build(), true);
});
$test_handler->addTest("Generate columns using ColumnsGenerator with nested columns", function (){
    $qb = new QueryBuilder();

    $cg = new ColumnsGenerator();
    $cg->setColumns(["id", "name", "last_name", "birthday", "category", "author"]);
    $users_cols = ["id", "fist_name", "last_name", "role"];
    $rules = [
        "category" => "category_name",
        "author" => ["users" => $users_cols]
    ];
    $cg->setRules($rules);
    $generated_cols = $cg->generateSchemeAssoc();

    $qb->select($generated_cols)->from("test");
    $qb->join(QueryBuilder::INNER_JOIN, "users", ["id" => "users.id"]);
    $qb->where(["name" => "User", QueryBuilder::OP_AND, "users.id" => 1]);
    return var_export($qb->build(), true);
});

// Start test bench
$test_handler->start();