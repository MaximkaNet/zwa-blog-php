<?php

namespace app\domain\categories;

require_once "../../core/utils/test.php";
use app\core\utils\Test;
use app\core\DatabaseConfiguration;
require_once "category.php";
require_once "categoryDataSource.php";

define("DB_CONFIG", new DatabaseConfiguration("localhost", "zwa-blog", "root", ""));

$test_handler = new Test();
$test_handler->addTest("Save category", function (){
    $data_source = new CategoryDataSource(DB_CONFIG);
    $category = new Category(null, "pythonf", "Pysdfes");
    $model_to_update = $data_source->select(null, ["where" => ["name" => $category->getName()]]);
    if(isset($model_to_update))
        return "Updated: " . $data_source->update($category->toAssoc(["id"]), ["where" => ["id" => $model_to_update["id"]]]);
    return "Last insert id: " . $data_source->insert($category->toAssoc(["id"]));
});
$test_handler->addTest("Select all categories", function (){
    $data_source = new CategoryDataSource(DB_CONFIG);
    return var_export($data_source->select([]), true);
});
$test_handler->addTest("Delete inserted category", function (){
    $data_source = new CategoryDataSource(DB_CONFIG);
    $category = new Category(null, "test", "test");
    $last_id = $data_source->insert($category->toAssoc(["id"]));
    return var_export($data_source->delete(["where" => ["id" => $last_id]]), true);
});
$test_handler->start();